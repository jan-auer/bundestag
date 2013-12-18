using System.Text;
using System;
using System.Linq;

namespace Btw.Benchmark
{
    public static class BenchmarkResultExtensions
    {
        public static string PrintPretty(this BenchmarkResult result)
        {
            var prettyBuilder = new StringBuilder();
            var header = String.Format("| {0,42}  | {1,25} |", "URL", "Time (ms)");
            var separator = Enumerable.Range(1, 75).Select(i => "-").Aggregate((c, d) => c + d);

            prettyBuilder.AppendLine(separator);
            prettyBuilder.AppendLine(header);
            prettyBuilder.AppendLine(separator);
            var targets = result.AggregatedTimes.OrderByDescending(time => time.Key.Uri.AbsoluteUri);
            foreach (var target in targets)
            {
                var targetName = target.Key.Uri.AbsoluteUri;
                var targetResult = target.Value;

                var targetDisplayName = targetName.Length < 40 ?
                    targetName :
                    "..." + targetName.Substring(targetName.Length - 35);
                var targetDisplayResult = Math.Round(targetResult, 2);

                var line = String.Format("| {0,42}  | {1,25} |", targetDisplayName, targetDisplayResult);
                prettyBuilder.AppendLine(line);
            }
            prettyBuilder.AppendLine(separator);
            prettyBuilder.AppendLine();
            prettyBuilder.AppendLine();

            return prettyBuilder.ToString();
        }
    }
}
