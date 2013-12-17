using System.Collections.Generic;
using Btw.Benchmark;
using System.Linq;
using System.Threading.Tasks;
using System;
using System.Threading;



namespace Btw.Benchmark
{
    public class Terminal
    {
        BenchmarkResult _results;

        public int DelayTime { get; private set; }

        public IList<BenchmarkTarget> Targets { get; private set; }

        public event BenchmarkingFinishedEventHandler OnBenchmarkingFinished;

        public Terminal(int delayTime, IList<BenchmarkTarget> targets)
        {
            DelayTime = delayTime;
            Targets = targets;
            _results = new BenchmarkResult();
        }

        public Terminal(int delayTime, IList<BenchmarkTarget> targets, BenchmarkingFinishedEventHandler benchmarkingFinishedEventHandler) : this (delayTime, targets)
        {
            OnBenchmarkingFinished = benchmarkingFinishedEventHandler;
        }

        public void Start()
        {
            Task.Factory.StartNew(run);
        }

        IList<BenchmarkTarget> arrangeTargets()
        {
            var totalCallCount = Targets.Sum(target => target.Rate);
            var sequentialQueue = Targets.Select(target => Enumerable.Range(0, target.Rate).Select(i => target.Url.AbsolutePath))
                                         .Aggregate((l, r) => l.Union(r));
            var callQueue = Targets.Shuffle(100);

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
                var time = watch.Measure(() => httpService.Get(target.Url));
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

            OnBenchmarkingFinished.Invoke(this, _results);
        }
    }
}