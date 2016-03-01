Why JSON-RPC?
=============

Why RPC? 
--------

RPC or REST styles depend on underlying architecture for application, but: 
 
1. RPC has no limits, you can implement REST style with RPC methods.
2. REST supposes that application operates on resources and forces developer to think that way.
3. It's harder to implement REST style.

Suppose, there is a rocket and it can be launched and directed to some target. 
With RPC it will be really easy to implement: 

    {"jsonrpc": "2.0", "method": "station.launchRocket", "params": {"rocketId": "R12", "target": [53.3, 32.3]}}
    
With REST it's not so easy, we should think what is resource here and how we can operate with it. But also 
keep in mind, that http://www.infoq.com/presentations/Simple-Made-Easy.

Why JSON? 
--------- 

Which format is better? It's definitely subjective answer.

Key points: 

1. JSON is more readable than XML, but not so readable as YAML.
2. XML and JSON is widely-used and has native support in most languages. 
3. If compare to XML than YAML and JSON need less memory to be transferred and while using.
4. YAML does not have specific protocols in opposite to XML and JSON.
5. XML supports attributes for values, but it's not problem for YAML or JSON. 
6. XML supports stream parsing, but it's not widely-used. 

JSON is readable, compact and widely-supported format. JSON is better until there is no need in XML. 