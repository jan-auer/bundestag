using CommandLine;
using System;
using System.Linq;

namespace Btw.Benchmark
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Initializing ...");
            var options = new Options();
            if (CommandLine.Parser.Default.ParseArguments(args, options))
            {
                var delayTime = Int32.Parse(options.DelayTime);
                var terminalCount = Int32.Parse(options.TerminalCount);
                var urls = options.Urls;
                var rates = options.Rates.ToList().ConvertAll(rate => Int32.Parse(rate));
                var targets = urls.Zip(rates, (url, rate) => new BenchmarkTarget(url, rate))
                    .ToList();
                var terminals = Enumerable.Range(0, terminalCount)
                    .Select(i => new Terminal(delayTime, targets))
                    .ToList();
                var runner = new BenchmarkRunner(terminals);
                runner.AllTerminalsFinished += terminalsFinished;
                Console.WriteLine("Measuring ...");
                runner.StartTerminals();
            }
            Console.ReadKey();
        }

        static void terminalsFinished(object sender, BenchmarkResult result)
        {
            Console.WriteLine("Finished.");
            Console.WriteLine();
            Console.WriteLine();
            Console.Write(result.PrintPretty());
        }
    }
}
