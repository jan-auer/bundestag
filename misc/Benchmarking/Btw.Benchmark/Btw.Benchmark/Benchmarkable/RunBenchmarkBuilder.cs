using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class RunBenchmarkBuilder
    {
        int _delayTime;

        int _terminalCount;

        IEnumerable<string> _urls;

        IEnumerable<int> _rates;

        public RunBenchmarkBuilder ForUrls(IEnumerable<string> urls)
        {
            _urls = urls;
            return this;
        }

        public RunBenchmarkBuilder WithCallRates(IEnumerable<int> rates)
        {
            _rates = rates;
            return this;
        }

        public RunBenchmarkBuilder WithDelayTime(int delayTime)
        {
            _delayTime = delayTime;
            return this;
        }

        public RunBenchmarkBuilder HavingTerminalCount(int terminalCount)
        {
            _terminalCount = terminalCount;
            return this;
        }

        public RunBenchmark Build()
        {
            var targets = _urls.Zip(_rates, (url, rate) => new BenchmarkTarget(url, rate))
                    .ToList();
            var terminals = Enumerable.Range(0, _terminalCount)
                    .Select(i => new TerminalBenchmark(_delayTime, targets))
                    .ToList();
            return new RunBenchmark(terminals);
        }
    }
}
