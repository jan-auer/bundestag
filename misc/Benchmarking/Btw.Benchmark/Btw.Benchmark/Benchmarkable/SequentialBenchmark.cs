using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class SequentialBenchmark : IBenchmarkable
    {
        public event BenchmarkingFinishedEventHandler BenchmarkingFinished;

        IEnumerable<IBenchmarkable> _benchmarkables;

        IEnumerator<IBenchmarkable> _benchmarkableEnumerator;

        public SequentialBenchmark(IEnumerable<IBenchmarkable> benchmarkables)
        {
            _benchmarkables = benchmarkables;
        }

        public void StartBenchmarking()
        {
            _benchmarkableEnumerator = _benchmarkables.GetEnumerator();
            if (_benchmarkableEnumerator.MoveNext()) benchmarkSingle(_benchmarkableEnumerator.Current);
        }

        void benchmarkSingle(IBenchmarkable benchmarkable)
        {
            benchmarkable.BenchmarkingFinished += new BenchmarkingFinishedEventHandler(benchmarkingFinished);
            benchmarkable.StartBenchmarking();
        }

        void benchmarkingFinished(object sender, BenchmarkResult result)
        {
            BenchmarkingFinished.Invoke(sender, result);
            if (_benchmarkableEnumerator.MoveNext()) benchmarkSingle(_benchmarkableEnumerator.Current);
        }
    }
}
