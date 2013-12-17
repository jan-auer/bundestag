using System;

namespace Btw.Benchmark
{
    public class BenchmarkTarget
    {
        public Uri Url { get; private set; }

        public int Rate { get; private set; }

        public BenchmarkTarget(Uri url, int rate)
        {
            Url = url;
            Rate = rate;
        }

        public BenchmarkTarget(string url, int rate)
            : this(new Uri(url), rate)
        { }
    }
}
