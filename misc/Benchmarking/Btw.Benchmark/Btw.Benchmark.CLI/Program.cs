using CommandLine;
using System;
using System.Linq;
using System.Text;

namespace Btw.Benchmark
{
    class Program
    {
        static int FinishedRunCount = 0;

        static int TotalRunCount = 0;

        static StringBuilder BenchmarkResultOutput = new StringBuilder();

        static void Main(string[] args)
        {
            Console.WriteLine("Initializing ...");
            var options = new Options();
            if (CommandLine.Parser.Default.ParseArguments(args, options))
            {
                var delayTimes = options.DelayTime.Select(time => Int32.Parse(time));
                var terminalCount = options.TerminalCount.Select(terminal => Int32.Parse(terminal));
                var runConfigs = delayTimes.Zip(terminalCount, (time, count) => new { time, count });
                var runCount = runConfigs.Count();
                var urls = options.Urls;
                var rates = options.Rates.ToList().ConvertAll(rate => Int32.Parse(rate));
                var runs = runConfigs.Select(config => (new RunBenchmarkBuilder())
                    .WithDelayTime(config.time)
                    .HavingTerminalCount(config.count)
                    .ForUrls(urls)
                    .WithCallRates(rates)
                    .Build());
                var sequence = new SequentialBenchmark(runs);
                sequence.BenchmarkingFinished += new BenchmarkingFinishedEventHandler(benchmarkingRunFinished);
                TotalRunCount = runs.Count();
                Console.WriteLine("Measuring ...");
                sequence.StartBenchmarking();
            }
            Console.ReadKey();
        }

        static void benchmarkingRunFinished(object sender, BenchmarkResult result)
        {
            var runDelayTime = Math.Round((sender as RunBenchmark).AverageDelayTime).ToString();
            var runTerminalCount = (sender as RunBenchmark).TerminalCount;

            BenchmarkResultOutput.AppendLine();
            BenchmarkResultOutput.AppendLine();
            BenchmarkResultOutput.AppendLine("Run " + FinishedRunCount++ + " (n=" + runTerminalCount + ", t=" + runDelayTime + ") :");
            BenchmarkResultOutput.AppendLine(result.PrintPretty());
            Console.WriteLine("Finished " + FinishedRunCount + "/" + TotalRunCount);
            if (FinishedRunCount == TotalRunCount)
            {
                Console.Write(BenchmarkResultOutput.ToString());
                Console.WriteLine();
                Console.WriteLine();
                Console.WriteLine("Press any key to exit");
            }
        }
    }
}
