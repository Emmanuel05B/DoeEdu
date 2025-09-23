import java.util.NoSuchElementException;

public class MyLinkedList<E> {
    // Node class (doubly linked)
    private static class Node<E> {
        E item;
        Node<E> next;
        Node<E> prev;

        Node(Node<E> prev, E element, Node<E> next) {
            this.item = element;
            this.next = next;
            this.prev = prev;
        }
    }

    private Node<E> first; // head
    private Node<E> last;  // tail
    private int size = 0;

    // Add element at end
    public void add(E e) {
        Node<E> newNode = new Node<>(last, e, null);
        if (last == null) {
            first = newNode; // first element
        } else {
            last.next = newNode;
        }
        last = newNode;
        size++;
    }

    // Add element at index
    public void add(int index, E e) {
        checkPositionIndex(index);

        if (index == size) { // add at end
            add(e);
        } else {
            Node<E> target = node(index);
            Node<E> newNode = new Node<>(target.prev, e, target);
            if (target.prev == null) {
                first = newNode;
            } else {
                target.prev.next = newNode;
            }
            target.prev = newNode;
            size++;
        }
    }

    // Get element at index
    public E get(int index) {
        checkElementIndex(index);
        return node(index).item;
    }

    // Remove element at index
    public E remove(int index) {
        checkElementIndex(index);
        return unlink(node(index));
    }

    private E unlink(Node<E> x) {
        E element = x.item;
        Node<E> next = x.next;
        Node<E> prev = x.prev;

        if (prev == null) {
            first = next;
        } else {
            prev.next = next;
            x.prev = null;
        }

        if (next == null) {
            last = prev;
        } else {
            next.prev = prev;
            x.next = null;
        }

        x.item = null;
        size--;
        return element;
    }

    // Utility: get node at index
    private Node<E> node(int index) {
        if (index < (size >> 1)) {
            Node<E> x = first;
            for (int i = 0; i < index; i++) x = x.next;
            return x;
        } else {
            Node<E> x = last;
            for (int i = size - 1; i > index; i--) x = x.prev;
            return x;
        }
    }

    private void checkElementIndex(int index) {
        if (!(index >= 0 && index < size))
            throw new IndexOutOfBoundsException("Index: " + index);
    }

    private void checkPositionIndex(int index) {
        if (!(index >= 0 && index <= size))
            throw new IndexOutOfBoundsException("Index: " + index);
    }

    public int size() {
        return size;
    }

    // Print list
    public void printList() {
        Node<E> x = first;
        while (x != null) {
            System.out.print(x.item + " ");
            x = x.next;
        }
        System.out.println();
    }

    // Test
    public static void main(String[] args) {
        MyLinkedList<String> list = new MyLinkedList<>();
        list.add("A");
        list.add("B");
        list.add("C");
        list.printList(); // A B C

        list.add(1, "X");
        list.printList(); // A X B C

        list.remove(2);
        list.printList(); // A X C

        System.out.println("Element at 1: " + list.get(1)); // X
        System.out.println("Size: " + list.size()); // 3
    }
}
