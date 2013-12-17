using System;
using System.Net;
using System.IO;

namespace Btw.Benchmark
{
    public class HttpRequestService
    {
        public string Get(Uri uri)
        {
            var request = WebRequest.Create(uri.AbsoluteUri);
            request.Method = "GET";
            var read = String.Empty;

            using (var response = request.GetResponse())
            {
                using(var reader = new StreamReader(response.GetResponseStream(), true))
                {
                    read = reader.ReadToEnd();
                }
            }

            return read;
        }
    }
}
