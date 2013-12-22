using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class BenchmarkResult
    {
        IDictionary<BenchmarkTarget, List<double>> _times;

        public IDictionary<BenchmarkTarget, List<double>> Times
        {
            get
            {
                if (_times == null) _times = new Dictionary<BenchmarkTarget, List<double>>();
                return _times;
            }
        }

        public IDictionary<BenchmarkTarget, double> AggregatedTimes
        {
            get
            {
                return Times.ToDictionary(targetTimes => targetTimes.Key, targetTimes => targetTimes.Value.Sum() / targetTimes.Value.Count);
            }
        }

        public double TotalTime
        {
            get
            {
                var flattenedTimes = Times.Select(times => times.Value).Aggregate((llist, rlist) => llist.Union(rlist).ToList());
                return flattenedTimes.Sum() / flattenedTimes.Count;
            }
        }
    }
}
