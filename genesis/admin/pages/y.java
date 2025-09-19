import java.io.*;
import java.util.*;
import javax.xml.parsers.*;
import org.w3c.dom.*;
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
            File xmlFile = new File("orders.xml");
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

                // Ensure the node is an element
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
            FileWriter fw = new FileWriter("order_summary.txt");
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