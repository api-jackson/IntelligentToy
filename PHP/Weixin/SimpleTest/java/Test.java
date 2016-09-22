import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Calendar;

public class Test{

	public static final int PORT = 8020;
	public static ServerSocket server;
	public static Socket socket;
	
	public static void main(String[] args) {
		System.out.println("waiting for client connect!");
		try {
			server = new ServerSocket(PORT);
			socket = server.accept();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		int i = 1;
		System.out.println("connect ok");
		while (true) {
			try {
				Thread.sleep(5*1000);
				System.out.println("Try: "+i++);
				socket.sendUrgentData(0XFF);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				
			} catch (IOException e) {
				// TODO Auto-generated catch block
				System.out.println("connect is down");
				e.printStackTrace();
			}
		}
	}
}