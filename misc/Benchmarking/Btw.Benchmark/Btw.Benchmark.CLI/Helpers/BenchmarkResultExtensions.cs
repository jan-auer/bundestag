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
            var header = String.Format("| {0,42}  | {1,10} |", "URL", "Time (ms)");
            var separator = Enumerable.Range(1, 60).Select(i => "-").Aggregate((c, d) => c + d);

            prettyBuilder.AppendLine(separator);
            prettyBuilder.AppendLine(header);
            prettyBuilder.AppendLine(separator);
            foreach (var target in result.AggregatedTimes)
            {
                var targetName = target.Key.Url.AbsoluteUri;
                var targetDisplayName = targetName.Length < 40 ?
                    targetName :
                    "..." + targetName.Substring(targetName.Length - 35);
                var targetResult = target.Value;
                var line = String.Format("| {0,42}  | {1,10} |", targetDisplayName, targetResult);
                prettyBuilder.AppendLine(line);
            }
            prettyBuilder.AppendLine(separator);
            prettyBuilder.AppendLine();
            prettyBuilder.AppendLine();

            return prettyBuilder.ToString();
        }
    }
}
