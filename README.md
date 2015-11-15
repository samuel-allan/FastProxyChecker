#Fast Proxy Checker Script
##How to use
The current latest and greatest version on the stack is 1.1 (I don't suggest using 1.0 because it's slow and uses very old techniques).

So, currently there are two ways to check proxies:
- Check a specific proxy (IP:PORT)
- Check a big list of proxies simultaneously (multi-threaded approach)

###Checking a specific proxy
Locate the _**checker.php**_ script, and GET it with the parameters ip, port and timeout:

```
checker.php?ip=1.2.3.4&port=80&timeout=20
```

This code will check the proxy 1.2.3.4:80 with a timeout of 20 seconds.
Results are returned in JSON format:

```json
{
    "result": {
        "success": true,
        "proxy": {
            "ip": "137.116.76.252",
            "port": "3128",
            "speed": 339
        }
    }
}
```




##Changelog for upcoming versions
**Version 1.2**
- Will have a version supporting includes (as a library)
- Adding proxy annonimity tests
- Will start supporting SOCKS proxies

##Changelog for new versions
**Version 1.1**
- Dropped command-line support, now using a browser-approach
- Switched to stable and working methods (100% accurate)
- Increased speed and multi-threading ability

##Version 1.1 Reference

##Known bugs in V1.1
1.  Can't handle SOCKS proxies (Returns 'Operation Timed Out' errors)

If you find any other bugs, please report them as an issue, or simply email me at work@samuelallan.info with the details.

##OLD Version Reference
**Version 1**

This version was entirely command-line only, and does not support any of the new features.
Use via command line:

$php index.php proxylist.txt 500 1 -nolicense -useonly

Recommended timeout is 1. Command line arguments as follow:

$php index.php [Proxy List File fmt PROXY:PORT] [Number of Threads 50-500 recommended] [timeout in seconds, 1 recommended] [-nolicense if you don't want to display the guff about license] [-useonly if you don't want the "-----" stuff]
