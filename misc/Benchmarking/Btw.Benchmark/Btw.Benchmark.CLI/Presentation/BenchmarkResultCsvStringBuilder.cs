using System.Text;
using System.Linq;
using System;

namespace Btw.Benchmark
{
    public class BenchmarkResultCsvStringBuilder : BenchmarkResultStringBuilderBase
    {
        char _delimiter;

        public BenchmarkResultCsvStringBuilder(char delimiter)
        {
            _delimiter = delimiter;
        }

        public override void AddResult(int number, RunBenchmark origin, BenchmarkResult result)
        {
            var runDelayTime = Math.Round(origin.AverageDelayTime).ToString();
            var runTerminalCount = origin.TerminalCount;
            var targets = result.AggregatedTimes.OrderByDescending(time => time.Key.Uri.AbsoluteUri);
            var total = result.TotalTime;
            var totalDisplayResult = Math.Round(total, 2);

            ResultBuilder.AppendLine("Run " + number++ + " (n=" + runTerminalCount + ", t=" + runDelayTime + ") :");
            foreach (var target in targets)
            {
                var targetDisplayResult = Math.Round(target.Value, 2);
                ResultBuilder.AppendLine(target.Key.Uri.AbsoluteUri + _delimiter + targetDisplayResult);
            }

            ResultBuilder.AppendLine("Total: " + totalDisplayResult);
            ResultBuilder.AppendLine();
        }
    }
}
