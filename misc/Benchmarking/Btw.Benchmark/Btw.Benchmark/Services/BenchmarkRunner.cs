using System.Collections.Generic;
using System.Linq;

namespace Btw.Benchmark
{
    public class BenchmarkRunner
    {
        Dictionary<Terminal, bool> _terminals;

        BenchmarkResult _result;

        public BenchmarkRunner(IList<Terminal> terminals)
        {
            _terminals = terminals.ToDictionary(terminal => terminal, _ => false);
            _result = new BenchmarkResult();
        }

        public event BenchmarkingFinishedEventHandler AllTerminalsFinished;

        public void StartTerminals()
        {
            _terminals.Keys.ToList().ForEach(terminal => 
                {
                    terminal.OnBenchmarkingFinished += terminalFinished;
                    terminal.Start();
                });
        }

        void terminalFinished(object sender, BenchmarkResult partBuilder)
        {
            var senderTerminal = sender as Terminal;
            var terminalTimes = partBuilder.AggregatedTimes;

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

            if(_terminals.All(terminal => terminal.Value)) AllTerminalsFinished.Invoke(this, _result);
        }
    }
}
