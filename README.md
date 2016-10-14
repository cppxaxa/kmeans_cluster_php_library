# K-MEANS Clusters PHP Library
It is a simple PHP library to integrate and get clusters with implementation of kmeans

# Live Demo
http://spider.nitt.edu/~cppxaxa/kmeans/

# Usage
You need to provide k value to get clusters.

$obj = new Wrapper();<br/>
$obj->k = 2;<br/>
$obj->limit = 10;<br/>
$obj->set = array();<br/>
$obj->set[] = new DataSet(10, 20);<br/>
$obj->set[] = new DataSet(10, 21);<br/>
$obj->set[] = new DataSet(30, 20);<br/>
$obj->set[] = new DataSet(35, 30);<br/>
<br/>
echo kmeans( json_encode($obj) );<br/>
