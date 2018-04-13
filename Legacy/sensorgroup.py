import sensor
from timer import tick
from datetime import datetime

# ARDUINO
class SensorGroup:
    # NO CALLBACK
    def __init__(self, callback, debug):
        self.sensors = []
        self.callback = callback
        self.debug = debug

    def add(self, sensor):
        sensor.calibrate(10, 1.04) #1.04 = 1 / 0.96; determined empirically
        self.sensors.append(sensor)

    def setup(self):
        self.begin = tick()
        time = self.begin
        self.trigger_times = [time] * len(self.sensors)
        self.counters = [0] * len(self.sensors)
        self.count_pass = [11, 8, 6] #determined empirically # What is this? # Empirically? Probably sensor specific
        self.people = 0
        if(self.debug):
            print("Time\tcount0\tcount1\tcount2\tpeople")

    #TODO: print debug mode
    def poll(self):
        #poll internal sensors
        #calculate stuff
        #possibly trigger self.callback
        val0 = self.sensors[0].read()
        if(val0 > 0):
            return
        val1 = self.sensors[1].read()
        if(val1 > 0):
            return
        val2 = self.sensors[2].read()
        if(val2 > 0):
            return
        #calculations
        time = tick()

        if(val0 < 0):
            self.trigger_times[0] = time
            self.counters[0] += 1

        if(val1 < 0 and self.counters[0] > self.count_pass[0] - 3):
            self.trigger_times[1] = time
            self.counters[1] += 1

        if(val2 < 0 and self.counters[0] > self.count_pass[0] - 1 and self.counters[1] > self.count_pass[1] - 3):
            self.trigger_times[2] = time
            self.counters[2] += 1

        # Instead of callback: write *something* do the serial port
        if (self.counters[0] >= self.count_pass[0]) and (self.counters[1] >= self.count_pass[1]) and (self.counters[2] >= self.count_pass[2]):
            self.callback(datetime.now())
            self.people += 1 #for debug purposes
            self.counters[0] -= self.count_pass[0]
            self.counters[1] -= self.count_pass[1]
            self.counters[2] -= self.count_pass[2]

        for i in range(0, 3):
            if(time - self.trigger_times[i] > 4.5):
                self.counters[i] = 0
                self.trigger_times[i] = time

        #debug printing
        if(self.debug):
            print(("%.2f" % (time - self.begin)) + "\t" + str(self.counters[0]) + "\t" + str(self.counters[1]) + "\t" + str(self.counters[2]) + "\t" + str(self.people))
