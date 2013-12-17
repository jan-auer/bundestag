using System;

namespace Btw.Benchmark
{
    public class IntHelpers
    {
        public static int GenerateRandomDeviationFor(int i, double lowerDeviationRatio, double upperDeviationRatio)
        {
            if (lowerDeviationRatio < 0 || lowerDeviationRatio > 1) throw new ArgumentOutOfRangeException();
            if (upperDeviationRatio < 0 || upperDeviationRatio > 1) throw new ArgumentOutOfRangeException();

            var random = new Random();
            var min = (int)Math.Round(i - (i * lowerDeviationRatio));
            var max = (int)Math.Round(i + (i * upperDeviationRatio));
            var approximation = random.Next(min, max);
            return approximation;
        }
    }
}
