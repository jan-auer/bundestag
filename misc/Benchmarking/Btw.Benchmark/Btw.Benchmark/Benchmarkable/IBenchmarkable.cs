namespace Btw.Benchmark
{
    public interface IBenchmarkable
    {
        event BenchmarkingFinishedEventHandler BenchmarkingFinished;

        void StartBenchmarking();
    }
}
