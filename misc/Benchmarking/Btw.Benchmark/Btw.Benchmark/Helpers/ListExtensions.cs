using System.Collections.Generic;
using System;

namespace Btw.Benchmark
{
    public static class ListExtensions
    {
        public static IList<T> Swap<T>(this IList<T> list, int indexA, int indexB)
        {
            T tmp = list[indexA];
            list[indexA] = list[indexB];
            list[indexB] = tmp;
            return list;
        }

        public static IList<T> Shuffle<T>(this IList<T> source, int factor)
        {
            var count = source.Count;
            var target = new T[count];
            source.CopyTo(target, 0);

            var random = new Random();
            for (int i = 0; i < factor; i++)
            {
                var pos1 = random.Next(count - 1);
                var pos2 = random.Next(count - 1);
                target = target.Swap(pos1, pos2) as T[];
            }

            return target;
        }

        public static IList<T> AddMany<T>(this IList<T> list, T item, int count)
        {
            for (int i = 0; i < count; i++)
            {
                list.Add(item);
            }
            return list;
        }
    }
}
