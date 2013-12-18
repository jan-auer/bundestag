using CommandLine;
using System.Text;
using System.Collections.Generic;
using CommandLine.Text;
using System;

namespace Btw.Benchmark
{
    public class Options
    {
        [ParserState]
        public IParserState LastParserState { get; set; }

        [OptionArray('t', "time", Required = true, HelpText = "Approximate delay time in milliseconds between calls. Please Specify one time per desired run.")]
        public string[] DelayTime { get; set; }

        [OptionArray('n', "count", Required=true, HelpText="Total terminal count per run. Please specify one terminal count per desired run.")]
        public string[] TerminalCount { get; set; }

        [OptionArray('u', "urls", Required = true, HelpText = "URLs to call.")]
        public string[] Urls { get; set; }

        [OptionArray('r', "rates", Required = true, HelpText = "Rates mapping to specified URLs.")]
        public string[] Rates { get; set; }

        [Option('f', "filepath", Required = false, DefaultValue = "", HelpText = "If set, the benchmarking result will be saved to a CSV specified herein.")]
        public string File { get; set; }

        [HelpOption]
        public string GetUsage()
        {
            var help = new HelpText
            {
                Heading = new HeadingInfo("URL Benchmark Client", "v.01"),
                Copyright = new CopyrightInfo("Jan Auer, Manuel Gerding, Philip Schäfer", 2013),
                AdditionalNewLineAfterOption = true,
                AddDashesToOption = true
            };
            help.AddPreOptionsLine("Licensed under MIT License.");
            help.AddOptions(this);

            if (LastParserState.Errors.Count > 0)
            {
                var errors = help.RenderParsingErrorsText(this, 2);
                if (!String.IsNullOrEmpty(errors))
                {
                    help.AddPreOptionsLine(String.Concat(Environment.NewLine, "ERROR(S):"));
                    help.AddPreOptionsLine(errors);
                }
            }

            return help;
        }
    }
}
