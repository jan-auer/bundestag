using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;

namespace LineCounter
{
    class Program
    {
        static void Main(string[] args)
        {
            string line = String.Empty;
            var i = 0;
            var file = args[0];
            var reader = new StreamReader(file);
            while ((line = reader.ReadLine()) != null)
            {
                i++;
            }

            reader.Close();
            Console.WriteLine(i.ToString());
            Console.ReadLine();
        }
    }
}
