Log Viwer Application
=====================

Provides access to web server access logs.
Log files are located inside local user folders (/home/username/logs/*.log).
Log format - Common Log Format (CLF), see http://httpd.apache.org/docs/2.4/mod/mod_log_config.html#formats.

Features:
1. Display last 10/25/50/100 lines of all log files in the user's logs folder
2. Display pager to navigate (previous/next 10/25/50/100 lines)
3. Ability to search for specific lines using:
- simple search
- regular expression
4. Filters:
- by log file
- by date and time range

LogReaderService allow to apply multiple filters at the same time.
For pagination used https://github.com/KnpLabs/KnpPaginatorBundle.
For parsing logs -https://github.com/kassner/log-parser.
Also used https://github.com/beberlei/DoctrineExtensions for queries with REGEXP.

All logs stored in DB. Application check log files for new content and save it to the database when appropriate.
Table structures (auto generated be Doctrine):
        #For files list
        CREATE TABLE IF NOT EXISTS `file_info` (
          `id` int(11) NOT NULL,
          `file_path` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
          `updated_at` datetime NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8

        #For files content
        CREATE TABLE IF NOT EXISTS `file_line` (
          `id` int(11) NOT NULL,
          `request` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
          `host` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
          `logname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `user` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
          `timestamp` datetime NOT NULL,
          `status` int(11) NOT NULL,
          `response_bytes` int(11) NOT NULL,
          `file_id` int(11) NOT NULL,
          `line_number` int(11) NOT NULL,
          `full_line` varchar(1000) COLLATE utf8_unicode_ci NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8

System requirements same as for Symfony 2.7.








