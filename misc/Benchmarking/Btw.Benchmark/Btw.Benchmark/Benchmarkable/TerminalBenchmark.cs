using System.Collections.Generic;
using Btw.Benchmark;
using System.Linq;
using System.Threading.Tasks;
using System;
using System.Threading;

namespace Btw.Benchmark
{
    public class TerminalBenchmark : IBenchmarkable
    {
        public event BenchmarkingFinishedEventHandler BenchmarkingFinished;

        BenchmarkResult _results;

        public int DelayTime { get; private set; }

        public IEnumerable<BenchmarkTarget> Targets { get; private set; }

        public TerminalBenchmark(int delayTime, IEnumerable<BenchmarkTarget> targets)
        {
            DelayTime = delayTime;
            Targets = targets;
            _results = new BenchmarkResult();
        }

        public void StartBenchmarking()
        {
            Task.Factory.StartNew(run);
        }

        IEnumerable<BenchmarkTarget> arrangeTargets()
        {
            var totalCallCount = Targets.Sum(target => target.Rate);
            var sequentialQueue = Targets.Select(target => Enumerable.Range(1, target.Rate).Select(i => target))
                                         .Aggregate((l, r) => l.Concat(r))
                                         .ToList();
            var callQueue = sequentialQueue.Shuffle(100);

            return callQueue;
        }

        void run()
        {
            var callQueue = arrangeTargets();

            foreach (var target in callQueue)
            {
                var delay = IntHelpers.GenerateRandomDeviationFor(DelayTime, 0.2d, 0.2d);
                var watch = new Stopwatch();
                var httpService = new HttpRequestService();
                var time = watch.Measure(() => httpService.Get(target.Uri));
                if (_results.Times.ContainsKey(target))
                {
                    _results.Times[target].Add(time);
                }
                else
                {
                    _results.Times[target] = new List<double>() { time };
                }
                Thread.Sleep(delay);
            }

            BenchmarkingFinished.Invoke(this, _results);
        }
    }
}