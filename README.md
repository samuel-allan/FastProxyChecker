FastProxyChecker
================
#Changelog for upcoming versions#
**Version 1.2**
- Will have a version supporting includes (as a library)
- Adding proxy annonimity tests
- Will start supporting SOCKS proxies

#Changelog for new versions#
**Version 1.1**
- Dropped command-line support, now using a browser-approach
- Switched to stable and working methods (100% accurate)
- Increased speed and multi-threading ability

#Version 1.1 Reference#

#Known bugs in V1.1#
1.  Can't handle SOCKS proxies (Returns 'Operation Timed Out' errors)

If you find any other bugs, please report them as an issue, or simply email me at work@samuelallan.info with the details.

#OLD Version Reference#
**Version 1**

This version was entirely command-line only, and does not support any of the new features.
Use via command line:

$php index.php proxylist.txt 500 1 -nolicense -useonly

Recommended timeout is 1. Command line arguments as follow:

$php index.php [Proxy List File fmt PROXY:PORT] [Number of Threads 50-500 recommended] [timeout in seconds, 1 recommended] [-nolicense if you don't want to display the guff about license] [-useonly if you don't want the "-----" stuff]
