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

        static BenchmarkResultConsoleStringBuilder ConsoleBuilder = new BenchmarkResultConsoleStringBuilder();

        static BenchmarkResultCsvStringBuilder CsvBuilder = new BenchmarkResultCsvStringBuilder(';');

        static string CsvFilePath = String.Empty;

        static void Main(string[] args)
        {
            Console.WriteLine("Initializing ...");
            var options = new Options();
            if (CommandLine.Parser.Default.ParseArguments(args, options))
            {
                CsvFilePath = options.File;
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

        static void benchmarkingRunFinished(IBenchmarkable sender, BenchmarkResult result)
        {
            ConsoleBuilder.AddResult(FinishedRunCount, sender as RunBenchmark, result);
            if (CsvFilePath != String.Empty) CsvBuilder.AddResult(++FinishedRunCount, sender as RunBenchmark, result);

            Console.WriteLine("Finished " + FinishedRunCount + "/" + TotalRunCount + " ...");

            if (FinishedRunCount >= TotalRunCount)
            {
                if (CsvFilePath != String.Empty)
                {
                    var csvContent = CsvBuilder.Build();
                    var exporter = new CsvExporter();
                    try
                    {
                        exporter.Save(csvContent, CsvFilePath);
                    }
                    catch (Exception)
                    {
                        Console.WriteLine("Saving CSV failed. Do you have the required access privileges?");
                    }
                }

                Console.Write(ConsoleBuilder.Build());
                Console.WriteLine();
                Console.WriteLine();
                Console.WriteLine("Press any key to exit.");
            }
        }
    }
}
