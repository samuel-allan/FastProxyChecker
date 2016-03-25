# Fast Proxy Checker Script
*(Short Notice 3/25/3016): Version 1.2 is available, and should be stable enough to use by the end of March. I recommend everyone in need of SOCKS proxy support to upgrade to version 1.2

## How to use
Currently there are two ways to check proxies:
- Check a specific proxy (IP:PORT)
- Check a big list of proxies simultaneously (multi-threaded approach)

### Checking a specific proxy
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

### Checking a big list of proxies with multi-threading enabled
Unless the list of proxies you want to check is really small, you'll want to use multi-threading which can speed up requests exponentially.

This feature works best if you supply a file with a list of proxies in it (Most free proxy listing websites will allow you to export proxies in IP:PORT format).

To check a list of proxies contained in file `abc.txt` with a timeout of 20 seconds:

```
localhost/checker.php?file=abc.txt&timeout=20
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






### Current flaws in 1.1
- Can't handle SOCKS proxies (Returns operation timed out error)
_No bugs present yet_

### Credits

> **Me** _(Samuel Allan)_ for creating and maintaining the code.

> **W. Al Maawali** for the script that V1.1 was built on.

> **Miyachung** for creating the script that V1.0 was wrapped around.

### Links
You can contact me (*Samuel Allan*) at _naclo3samuel@gmail.com_ or _work@samuelallan.info_

**W. Al Maawali** - [Link to the script that made V1.1 possible](https://www.digi77.com/validating-proxy-via-php/)

You can contact **Miyachung** at _Miyachung@hotmail.com_

