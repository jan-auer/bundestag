using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class BenchmarkResult
    {
        Dictionary<BenchmarkTarget, List<double>> _times;

        public Dictionary<BenchmarkTarget, List<double>> Times
        {
            get
            {
                if (_times == null) _times = new Dictionary<BenchmarkTarget, List<double>>();
                return _times;
            }
        }

        public Dictionary<BenchmarkTarget, double> AggregatedTimes
        {
            get
            {
                return Times.ToDictionary(targetTimes => targetTimes.Key, targetTimes => targetTimes.Value.Sum() / targetTimes.Value.Count);
            }
        }
    }
}
