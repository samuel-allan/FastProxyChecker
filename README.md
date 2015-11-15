#Fast Proxy Checker Script
##How to use
The current latest and greatest version on the stack is 1.1 (I don't suggest using 1.0 because it's slow and uses very old techniques).

So, currently there are two ways to check proxies:
- Check a specific proxy (IP:PORT)
- Check a big list of proxies simultaneously (multi-threaded approach)

###Checking a specific proxy
Locate the *checker.php* script, and GET it with the parameters *ip*, *port* and *timeout*:

```
checker.php?ip=137.116.76.252&port=3128&timeout=20
```

This code will check the proxy 137.116.76.252:3128 with a timeout of 20 seconds.

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
And in the case of faliure you will get the reason why it failed (curl_error direct return):

```json
{
   "result":{
      "success":false,
      "error":"Operation timed out after 1000 milliseconds with 0 bytes received",
      "proxy":{
         "ip":"186.227.185.123",
         "port":"80"
      }
   }
}
```

###Checking a big list of proxies with multi-threading enabled
Unless the list of proxies you want to check is really small, you'll want to use multi-threading which can speed up requests exponentially.

This feature works best if you supply a file with a list of proxies in it (Most free proxy listing websites will allow you to export proxies in IP:PORT format).

To check a list of proxies contained in file `abc.txt` with a timeout of 20 seconds:

```
localhost/checker.php?file=proxylist.txt&timeout=20
```

This uses multi-threading and hence instead of taking 200 seconds for 10 proxies 
(20 secs timeout * 10 proxies), it will take roughly 30 seconds to finish.

However, the results are going to be a bit different this time, because you've checked a big list of proxies instead of one:

```json
{
   "results":[
      {
         "success":true,
         "proxy":{
            "ip":"186.227.185.123",
            "port":"80",
            "speed":2300
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"186.227.56.162",
            "port":"80",
            "speed":623
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"221.212.74.203",
            "port":"63163",
            "speed":907
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"180.166.112.47",
            "port":"8888",
            "speed":1468
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"221.212.74.203",
            "port":"12",
            "speed":802
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"221.212.74.204",
            "port":"12",
            "speed":779
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"221.212.74.204",
            "port":"19452",
            "speed":803
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"219.237.16.38",
            "port":"80",
            "speed":12909
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"221.212.74.204",
            "port":"933",
            "speed":874
         }
      },
      {
         "success":true,
         "proxy":{
            "ip":"131.0.168.86",
            "port":"80",
            "speed":8802
         }
      }
   ]
}  
```

As you can see from the json, it has an array of 'results' with results for each and every proxy in the list.






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
