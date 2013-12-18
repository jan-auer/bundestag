using System.Text;
namespace Btw.Benchmark
{
    public abstract class BenchmarkResultStringBuilderBase
    {
        protected StringBuilder ResultBuilder;

        public BenchmarkResultStringBuilderBase()
        {
            ResultBuilder = new StringBuilder();
        }

        public abstract void AddResult(int number, RunBenchmark origin, BenchmarkResult result);

        public string Build()
        {
            return ResultBuilder.ToString();
        }
    }
}
