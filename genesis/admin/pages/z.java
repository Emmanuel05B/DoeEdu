
/*
SOLIDitech Junior Java Challenge – Simulation

Duration: 2 hours
Mode: Command-line Java application
Instructions: Read carefully, write clean, object-oriented code, handle exceptions, and submit your source code in a zipped folder.

Task: Customer Order XML Processor

Background:
You are building a small utility for a fictional ISP to process customer orders received in XML files. Each XML file contains multiple orders. Each order includes the customer name, email, service type, and subscription cost.

Sample XML (orders.xml):

<Orders>
    <Order>
        <CustomerName>John Doe</CustomerName>
        <Email>john.doe@example.com</Email>
        <Service>Fibre</Service>
        <Cost>499.99</Cost>
    </Order>
    <Order>
        <CustomerName>Jane Smith</CustomerName>
        <Email>jane.smith@example.com</Email>
        <Service>Satellite</Service>
        <Cost>699.99</Cost>
    </Order>
    <!-- More orders -->
</Orders>


Requirements:

Parse the XML file: Read all orders from orders.xml.

Process the data:

Calculate the total revenue from all orders.

Count the number of orders for each service type.

Output:

Write a new file order_summary.txt containing:

Total Revenue: R<total>
Fibre Orders: <count>
Satellite Orders: <count>
LTE Orders: <count>


Replace <total> and <count> with actual values.

Error Handling:

Gracefully handle file not found or malformed XML.

Print a meaningful message to the console if an error occurs.

Code Quality:

Use OOP principles: at least one class representing an Order.

Use Collections appropriately (e.g., List, Map).

Code should be clean, readable, and well-structured.

Bonus (Optional, if time permits):

Sort orders alphabetically by customer name in the summary.

Allow the program to accept the XML filename as a command-line argument.*/










import java.io.*;
import java.util.*;
import javax.xml.parsers.*;
import org.w3c.dom.*;                    //for Element, Node and NodeList
import org.xml.sax.SAXException;

// Class representing a single order
class Order {
    private String customerName;
    private String email;
    private String service;
    private double cost;

    public Order(String customerName, String email, String service, double cost) {
        this.customerName = customerName;
        this.email = email;
        this.service = service;
        this.cost = cost;
    }

    // Getters
    public String getCustomerName() { return customerName; }
    public String getEmail() { return email; }
    public String getService() { return service; }
    public double getCost() { return cost; }
}

public class OrderProcessor {

    public static void main(String[] args) {

        List<Order> orders = new ArrayList<>(); // List to store orders

        // Use a try-catch block to handle file parsing and IO exceptions
        try {
            // 1. Load the XML file
            File xmlFile = new File("orders.xml");                 //check out file class for more methods like .exists();
            if (!xmlFile.exists()) {
                System.out.println("Error: orders.xml file not found!");
                return; // Exit program if file missing
            }

            // 2. Create DocumentBuilderFactory and DocumentBuilder
            DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
            DocumentBuilder builder = factory.newDocumentBuilder();

            // 3. Parse XML file and normalize
            Document doc = builder.parse(xmlFile);
            doc.getDocumentElement().normalize();

            // 4. Get all <Order> nodes
            NodeList orderNodes = doc.getElementsByTagName("Order");

            // 5. Iterate over each <Order> node
            for (int i = 0; i < orderNodes.getLength(); i++) {
                Node node = orderNodes.item(i);

                // Ensure the node is an element...to be able to access its childrn(attributes)
                if (node.getNodeType() == Node.ELEMENT_NODE) {
                    Element orderElement = (Element) node;

                    // Extract data from XML tags
                    String customerName = orderElement.getElementsByTagName("CustomerName").item(0).getTextContent();
                    String email = orderElement.getElementsByTagName("Email").item(0).getTextContent();
                    String service = orderElement.getElementsByTagName("Service").item(0).getTextContent();
                    double cost = Double.parseDouble(orderElement.getElementsByTagName("Cost").item(0).getTextContent());

                    // Create Order object and add to list
                    orders.add(new Order(customerName, email, service, cost));
                }
            }

            // 6. Process orders: calculate total revenue and count per service
            double totalRevenue = 0.0;
            Map<String, Integer> serviceCount = new HashMap<>();

            for (Order order : orders) {
                totalRevenue += order.getCost();
                serviceCount.put(order.getService(), serviceCount.getOrDefault(order.getService(), 0) + 1);
            }

            // 7. Write summary to file
            FileWriter fw = new FileWriter("order_summary.txt");  // why not print writer ..same but no formatting methods like println..just print
            BufferedWriter bw = new BufferedWriter(fw);    

            bw.write(String.format("Total Revenue: R%.2f%n", totalRevenue));
            // For each service type, write count (if missing, show 0)
            bw.write("Fibre Orders: " + serviceCount.getOrDefault("Fibre", 0) + "\n");
            bw.write("Satellite Orders: " + serviceCount.getOrDefault("Satellite", 0) + "\n");
            bw.write("LTE Orders: " + serviceCount.getOrDefault("LTE", 0) + "\n");

            bw.close(); // Close file
            System.out.println("Order summary generated successfully in order_summary.txt");

        } catch (SAXException e) {
            System.out.println("Error: Malformed XML detected!");
        } catch (ParserConfigurationException e) {
            System.out.println("Error: Parser configuration problem!");
        } catch (IOException e) {
            System.out.println("Error: IO Exception occurred!");
        } catch (NumberFormatException e) {
            System.out.println("Error: Invalid number format in XML!");
        }
    }
}
/*
Node & NodeList

Node = an interface provided by the DOM library representing one node in the XML tree.

Could be an element (<Order>), text (John Doe), comment, attribute, etc.

NodeList = an interface representing a collection of nodes, like all <Order> elements under <Orders>.
*/


//       https://www.geeksforgeeks.org/java/java-dom-parser-1/

................XML.....................

Tree structure → nodes → elements → text

You usually:

Load the file into memory (DocumentBuilder)

Get nodes by tag name (getElementsByTagName)

Access child elements or text (getTextContent)

<Orders>             
    <Order>                //Node
        <Name>John</Name>     //Element/child
        <Surname>Doe</Surname>
        <Email>john.doe@example.com</Email>
        <Cost>499.99</Cost>
    </Order>
    <Order>
        <Name>Jane</Name>
        <Surname>Smith</Surname>
        <Email>jane.smith@example.com</Email>
        <Cost>699.99</Cost>
    </Order>
</Orders>



..................JSON...........................

Structure → objects → key-value pairs → values

You usually:

Load the JSON file into memory (using a library like Gson or Jackson)

Parse it into objects or maps

Access fields directly by key

JSON Example
{
  "orders": [
    {
      "name": "John",
      "surname": "Doe",
      "email": "john.doe@example.com",
      "cost": 499.99
    },
    {
      "name": "Jane",
      "surname": "Smith",
      "email": "jane.smith@example.com",
      "cost": 699.99
    }
  ]
}
///////////////////////Access/Read/////////////////////
Gson gson = new Gson();
OrdersWrapper wrapper = gson.fromJson(jsonString, OrdersWrapper.class);
List<Order> orders = wrapper.getOrders();

for (Order order : orders) {
    System.out.println(order.getName());
    System.out.println(order.getCost());
}



 


...................CSV..................

Structure → rows → columns → values

You usually:

Open the CSV file (e.g., with BufferedReader)

Read line by line

Split each line by delimiter (usually comma)

Map values to object fields

CSV Example
Name,Surname,Email,Cost
John,Doe,john.doe@example.com,499.99
Jane,Smith,jane.smith@example.com,699.99


///////////////////////Access/Read/////////////////////

BufferedReader br = new BufferedReader(new FileReader("orders.csv"));
String line = br.readLine(); // Skip header
while ((line = br.readLine()) != null) {
    String[] fields = line.split(",");
    Order order = new Order(fields[0], fields[1], fields[2], Double.parseDouble(fields[3]));
    System.out.println(order.getName());
}
br.close();


................TXT / Plain Text..................

Structure → lines → text

You usually:

Open the file (e.g., BufferedReader)

Read line by line

Process each line (split, parse, or map to objects)

TXT Example (orders.txt):

John,Doe,john.doe@example.com,499.99
Jane,Smith,jane.smith@example.com,699.99


Access / Read Example:

BufferedReader br = new BufferedReader(new FileReader("orders.txt"));
String line;
while ((line = br.readLine()) != null) {
    String[] fields = line.split(","); // split CSV-style
    Order order = new Order(fields[0], fields[1], fields[2], Double.parseDouble(fields[3]));
    System.out.println(order.getName());
}
br.close();






FrOM my Notes. 


Text I/O
Binary I/O 
Object I/O     
