using System;

namespace Btw.Benchmark
{
    public class Stopwatch
    {
        public double Measure(Action measurable)
        {
            var watch = new System.Diagnostics.Stopwatch();
            watch.Start();
            measurable.Invoke();
            watch.Stop();
            return watch.ElapsedMilliseconds;
        }
    }
}
