using System.IO;
namespace Btw.Benchmark
{
    public class CsvExporter
    {
        public void Save(string content, string filepath)
        {
            using (var file = new StreamWriter(filepath))
            {
                file.Write(content);
                file.Close();
            }
        }
    }
}
