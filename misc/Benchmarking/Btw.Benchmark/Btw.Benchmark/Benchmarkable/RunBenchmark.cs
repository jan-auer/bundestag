using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class RunBenchmark : IBenchmarkable
    {
        public event BenchmarkingFinishedEventHandler BenchmarkingFinished;

        Dictionary<TerminalBenchmark, bool> _terminals;

        BenchmarkResult _result;

        public double AverageDelayTime
        {
            get
            {
                return _terminals.Keys.Sum(terminal => terminal.DelayTime) / TerminalCount;
            }
        }

        public double TerminalCount
        {
            get
            {
                return _terminals.Keys.Count();
            }
        }

        public RunBenchmark(IList<TerminalBenchmark> terminals)
        {
            _terminals = terminals.ToDictionary(terminal => terminal, _ => false);
            _result = new BenchmarkResult();
        }

        public void StartBenchmarking()
        {
            _terminals.Keys.ToList().ForEach(terminal =>
            {
                terminal.BenchmarkingFinished += terminalFinished;
                terminal.StartBenchmarking();
            });
        }

        void terminalFinished(object sender, BenchmarkResult partResult)
        {
            var senderTerminal = sender as TerminalBenchmark;
            var terminalTimes = partResult.AggregatedTimes;

            foreach (var times in terminalTimes)
            {
                if (_result.Times.ContainsKey(times.Key))
                {
                    _result.Times[times.Key].Add(times.Value);
                }
                else
                {
                    _result.Times[times.Key] = new List<double>() { times.Value };
                } 
            }
            _terminals[senderTerminal] = true;

            if (_terminals.All(terminal => terminal.Value)) BenchmarkingFinished.Invoke(this, _result);
        }
    }
}
