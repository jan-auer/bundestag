using System;

namespace Btw.Benchmark
{
    public class BenchmarkTarget
    {
        public Uri Uri { get; private set; }

        public int Rate { get; private set; }

        public BenchmarkTarget(Uri uri, int rate)
        {
            Uri = uri;
            Rate = rate;
        }

        public BenchmarkTarget(string uri, int rate)
            : this(new Uri(uri), rate)
        { }
    }
}
