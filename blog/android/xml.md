# xml解析

XML解析
===========================

* 3种解析方式
    * DOM：一次性加载，耗内存，但接口比较友好
    * SAX：Simple API for XML，逐行扫描，可以做到边扫描边解析，事件驱动
    * PULL


本节内容请参考com.ayo.sample.xml.DemoXmlActivity

#####

模板xml：
```xml
<xml_api_reply version="1">
    <weather module_id="0" tab_id="0" mobile_row="0" mobile_zipped="1" row="0" section="0">
        <forecast_information>
            <city data="Beijing, Beijing"/>
            <postal_code data="beijing"/>
            <latitude_e6 data=""/>
            <longitude_e6 data=""/>
            <forecast_date data="2012-07-24"/>
            <current_date_time data="2012-07-24 15:30:00 +0000"/>
            <unit_system data="SI"/>
        </forecast_information>
        <current_conditions>
            <condition data="多云"/>
            <temp_f data="77"/>
            <temp_c data="25"/>
            <humidity data="湿度： 78%"/>
            <icon data="/ig/images/weather/cn_cloudy.gif"/>
            <wind_condition data="风向： 东北、风速：2 米/秒"/>
        </current_conditions>
        <forecast_conditions>
            <day_of_week data="周二"/>
            <low data="22"/>
            <high data="30"/>
            <icon data="/ig/images/weather/cn_cloudy.gif"/>
            <condition data="多云"/>
        </forecast_conditions>
        <forecast_conditions>
            <day_of_week data="周三"/>
            <low data="24"/>
            <high data="30"/>
            <icon data="/ig/images/weather/chance_of_rain.gif"/>
            <condition data="可能有雨"/>
        </forecast_conditions>
        <forecast_conditions>
            <day_of_week data="周四"/>
            <low data="25"/>
            <high data="29"/>
            <icon data="/ig/images/weather/thunderstorm.gif"/>
            <condition data="雷阵雨"/>
        </forecast_conditions>
        <forecast_conditions>
            <day_of_week data="周五"/>
            <low data="24"/>
            <high data="31"/>
            <icon data="/ig/images/weather/chance_of_storm.gif"/>
            <condition data="可能有暴风雨"/>
        </forecast_conditions>
    </weather>
</xml_api_reply>
```

```java
public static String xml = "<xml_api_reply version=\"1\">\n" +
        "    <weather module_id=\"0\" tab_id=\"0\" mobile_row=\"0\" mobile_zipped=\"1\" row=\"0\" section=\"0\">\n" +
        "        <forecast_information>\n" +
        "            <city data=\"Beijing, Beijing\"/>\n" +
        "            <postal_code data=\"beijing\"/>\n" +
        "            <latitude_e6 data=\"\"/>\n" +
        "            <longitude_e6 data=\"\"/>\n" +
        "            <forecast_date data=\"2012-07-24\"/>\n" +
        "            <current_date_time data=\"2012-07-24 15:30:00 +0000\"/>\n" +
        "            <unit_system data=\"SI\"/>\n" +
        "        </forecast_information>\n" +
        "        <current_conditions>\n" +
        "            <condition data=\"多云\"/>\n" +
        "            <temp_f data=\"77\"/>\n" +
        "            <temp_c data=\"25\"/>\n" +
        "            <humidity data=\"湿度： 78%\"/>\n" +
        "            <icon data=\"/ig/images/weather/cn_cloudy.gif\"/>\n" +
        "            <wind_condition data=\"风向： 东北、风速：2 米/秒\"/>\n" +
        "        </current_conditions>\n" +
        "        <forecast_conditions>\n" +
        "            <day_of_week data=\"周二\"/>\n" +
        "            <low data=\"22\"/>\n" +
        "            <high data=\"30\"/>\n" +
        "            <icon data=\"/ig/images/weather/cn_cloudy.gif\"/>\n" +
        "            <condition data=\"多云\"/>\n" +
        "        </forecast_conditions>\n" +
        "        <forecast_conditions>\n" +
        "            <day_of_week data=\"周三\"/>\n" +
        "            <low data=\"24\"/>\n" +
        "            <high data=\"30\"/>\n" +
        "            <icon data=\"/ig/images/weather/chance_of_rain.gif\"/>\n" +
        "            <condition data=\"可能有雨\"/>\n" +
        "        </forecast_conditions>\n" +
        "        <forecast_conditions>\n" +
        "            <day_of_week data=\"周四\"/>\n" +
        "            <low data=\"25\"/>\n" +
        "            <high data=\"29\"/>\n" +
        "            <icon data=\"/ig/images/weather/thunderstorm.gif\"/>\n" +
        "            <condition data=\"雷阵雨\"/>\n" +
        "        </forecast_conditions>\n" +
        "        <forecast_conditions>\n" +
        "            <day_of_week data=\"周五\"/>\n" +
        "            <low data=\"24\"/>\n" +
        "            <high data=\"31\"/>\n" +
        "            <icon data=\"/ig/images/weather/chance_of_storm.gif\"/>\n" +
        "            <condition data=\"可能有暴风雨\"/>\n" +
        "        </forecast_conditions>\n" +
        "    </weather>\n" +
        "</xml_api_reply>";
```

##1 DOM方式

* DOM
    * DOM方式解析xml是先把xml文档都读到内存中
    * 如果XML文件很大的时候，处理效率就会变得比较低

```java
//使用InputStream或者String初始化一个org.w3c.dom.Document对象，代表一个xml文档的DOM树
//用字符串xml
javax.xml.parsers.DocumentBuilder myDocBuilder = javax.xml.parsers.DocumentBuilderFactory.newInstance().newDocumentBuilder();
org.w3c.dom.Document document = myDocBuilder.parse(new InputSource(new StringReader(xml字符串)));

//用InputStream
javax.xml.parsers.DocumentBuilder myDocBuilder = javax.xml.parsers.DocumentBuilderFactory.newInstance().newDocumentBuilder();
org.w3c.dom.Document document = myDocBuilder.parse(new InputSource(inputStream));

//用本地文件路径
String path = "file:///data/data/com.example.androiddemo/network.xml"
javax.xml.parsers.DocumentBuilder myDocBuilder = javax.xml.parsers.DocumentBuilderFactory.newInstance().newDocumentBuilder();
org.w3c.dom.Document document = myDocBuilder.parse(path);

//获取根节点，以根节点为起始，访问其他子节点
Element root=document.getDocumentElement();

//获取某个标签对应的所有子节点
NodeList nodes=root.getElementsByTagName("forecast_conditions");

//遍历子节点列表
//解析出未来5天的天气预报
for(int i = 0; i < nodes.getLength(); i++){
    //这是一个forecast_conditions标签
    Node node = nodes.item(i);
    Log.i("dom", "==============begin：" + node.getNodeName());
    NodeList children = node.getChildNodes();
    for(int j = 0; j < children.getLength(); j++){
        //遍历子节点
        Node n = children.item(j);
        Log.i("dom", "子节点：" + n.getNodeName() + "==>" + n.getNodeValue());
        NamedNodeMap attrs = n.getAttributes();
        if(attrs != null){
            for(int k = 0; k < attrs.getLength(); k++){
                //遍历attr
                Log.i("dom", "----" + attrs.item(k).getNodeName() + "==>" + attrs.item(k).getNodeValue());
            }
        }
    }
    Log.i("dom", "==============over\n\n");
}

//注意，老是有个名为#text的子节点，value还是空，也没有属性，尝试去掉空格和换行，还报错

```


##2 SAX方式

* sax：
    * 速度更快，更有效，占用内存更少
    * 逐行扫描，可以做到边扫描边解析
    * SAX 可以在解析文档的任意时刻停止解析，非常适用于 Android 等移动设备
    * 基于事件驱动
    * 不用解析完整个文档，在按内容顺序解析文档过程中， SAX 会判断当前读到的字符是否符合 XML 文件语法中的某部分。如果符合某部分，则会触发事件
    * 所谓触发事件，就是调用一些回调方法
    * 事件处理器是 org.xml.sax 包中 ContentHander 、 DTDHander 、 ErrorHandler ，以及 EntityResolver 这 4 个接口
        * ContentHander：setContentHandler(ContentHandler h)，XML 文档标签的开始与结束，接收字符数据，跳过实体，接收元素内容中可忽略的空白等
        * DTDHander：setDTDHandler(DTDHandler h)，处理 DTD 解析时产生的相应事件
        * ErrorHandler：setErrorHandler(ErrorHandler h)，处理 XML 文档时产生的错误
        * EntityResolver：setEntityResolver(EntityResolver e)，处理外部实体


* 关于ContentHandler：
    * startDocument() ，当遇到文档的开头的时候，调用这个方法，可以在其中做一些预处理的工作
    * endDocument()，当文档结束的时候，调用这个方法，可以在其中做一些善后的工作
    * startElement(String namespaceURI, String localName,String qName, Attributes atts)
        * 当读到开始标签的时候，会调用这个方法
        * namespaceURI 就是命名空间
        * localName 是不带命名空间前缀的标签名
        * qName 是带命名空间前缀的标签名
        * 通过 atts 可以得到所有的属性名和相应的值
    * endElement(String uri, String localName, String name)，在遇到结束标签的时候，调用这个方法
    * characters(char[] ch, int start, int length)
        * 这个方法用来处理在 XML 文件中读到的内容。例如： <high  data="30"/> 主要目的是获取 high 标签中的值
        * 一个参数用于存放文件的内容
        * 后面两个参数是读到的字符串在这个数组中的起始位置和长度，使用 new String(ch,start,length) 就可以获取内容

示例：
<?xml version="1.0"?>

<weather>              ---------->         startElement

<forecast_information> ---------->         startElement

<city>                 ---------->         startElement

beijing                ---------->        characters

</city>                ---------->        endElement

</forecast_information > ---------->        endElement

</weather >            ---------->        endElement

文档结束                ---------->        endDocument()

```java
private void parseBySax(){
    try {
        SAXParserFactory saxFactory = SAXParserFactory.newInstance();
        SAXParser saxParser = saxFactory.newSAXParser();//利用获取到的对象创建一个解析器

        //parse方式1
        XMLContentHandler handler = new XMLContentHandler();//设置defaultHandler
        StringReader r = new StringReader(xml);
        saxParser.parse(new InputSource(r), handler);//进行解析
        r.close();
        /* parse方式2
        XMLReader xmlReader =saxFactory.newSAXParser().getXMLReader(); //获取一个XMLReader
        xmlReader.setContentHandler(handler);
        xmlReader.parse(newInputSource(stream));
        */

    } catch (Exception e) {
        e.printStackTrace();
    }
}

public class XMLContentHandler extends DefaultHandler {
    private static final String TAG = "XMLContentHandler";

    @Override
    public void characters(char[] ch, int start, int length)
            throws SAXException {
        Log.i(TAG, "解析内容："+new String(ch,start,length));
    }
    @Override
    public void endDocument() throws SAXException {
        super.endDocument();
        Log.i(TAG, "文档解析完毕。");
    }
    @Override
    public void endElement(String uri, String localName, String qName)
            throws SAXException {
        Log.i(TAG, localName+"解析完毕");
    }
    @Override
    public void startDocument() throws SAXException {
        Log.i(TAG, "开始解析... ...");
    }
    @Override
    public void startElement(String uri, String localName, String qName,
                             Attributes attributes) throws SAXException {
        Log.i(TAG, "解析元素："+localName);

        if(localName.equals("high")){
            Log.i(TAG, "解析元素："+localName);
            if(attributes != null){
                for(int i = 0; i < attributes.getLength(); i++){
                    Log.i(TAG, "====" + attributes.getLocalName(i) + ": " + attributes.getQName(i) + ": " + attributes.getValue(i));
                }
            }
        }
    }
}
```

##3 PULL

http://www.tuicool.com/articles/IvQvyq