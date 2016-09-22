//package test;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.RandomAccessFile;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.SocketException;
import java.net.URLDecoder;
import java.nio.Buffer;
import java.util.Date;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedList;

//import org.json.JSONException;
//import org.json.JSONObject;

import org.json.*;

public class Socket_Socket {

	public static void main(String[] args) {
		// TODO Auto-generated method stub
		new Thread(new ServerOutput()).start();
		new Thread(new ServerInput()).start();
		new Thread(new OnlineServer()).start();
		new Thread(new TestOnline()).start();
	}

}

/**
 * 
 * @author Administrator
 * 此类是处理java服务器的输出流的类，需要设备或PHP端建立长连接
 */
class ServerOutput implements Runnable
{

    public static final int PORT = 8000;//监听的端口号
    
    public static HashMap<Integer, Socket> clientList = new HashMap<Integer, Socket>(); // 输出流连接的列表
	public static HashMap<Integer, Boolean> onlineFlag = new HashMap<Integer, Boolean>(); // 设备是否在线的记录表
    
	public void run() {
		// TODO Auto-generated method stub
		ServerSocket serverSocket = null;
		try   
        {    
            serverSocket = new ServerSocket(PORT);   
            while (true)   
            {   
            	//不断等待新的客户端连接   
                Socket client = serverSocket.accept();
                
                //客户端连接后，首先读取客户端发来的客户号
                try 
                {
                	 DataInputStream dis = new DataInputStream(client.getInputStream());
                	 DataOutputStream dos = new DataOutputStream(client.getOutputStream());
                	 byte[] buffer = new byte[1024];
                	 dis.read(buffer);
                	 System.out.println(new String(buffer).trim());
                	 JSONObject jsonInfo = new JSONObject(new String(buffer).trim());
                	 Integer clientNum = Integer.parseInt(jsonInfo.getString("id"));
                	 
                	 /* 检测clientList中是否存在此对象，若存在，则替换，否则加入 */
                	 if(ServerOutput.clientList.containsKey(clientNum))
                	 {
                		 ServerOutput.clientList.remove(clientNum);
                		 ServerOutput.clientList.put(clientNum, client);
                	 }
                	 else
                		 ServerOutput.clientList.put(clientNum, client);
                	 
                	 System.out.println("Output: 客户端 " + clientNum.toString() + " 连接成功");
                	 dos.write("ok".getBytes());
                	 
                	 /* 0 是本机PHP程序的客户号，此处进行相应的函数处理 */
                	 if(clientNum == 0)
                	 {
                		 
                	 }
                	 
                	 /* 终端发送的消息，此处进行相应的函数处理 */
                	 else 
                	 {	
                		// 终端连接时，其处理标志位为 false，表示还没有进行数据（文件）传输
                		if(CommandQueue.processFlag.containsKey(clientNum))
                		{
                			CommandQueue.processFlag.remove(clientNum);
                		}
                		CommandQueue.processFlag.put(clientNum, new Boolean(false)); 
                		
                		System.out.println("process flag: "+CommandQueue.processFlag.get(clientNum)+" == false");
                		/*********** 2015-11-17 start *********/
                		// 当终端设备初次连接时，将为该终端新建命令队列，并启动该队列
                		if(!ServerOutput.onlineFlag.containsKey(clientNum))
                		{
                			CommandQueue.commandQueue.put(clientNum, new LinkedList<JSONObject>());
                			new Thread(new CommandQueue(clientNum)).start();
                		}
//                		ServerOutput.onlineFlag.put(clientNum, new Boolean(true));
                		/*********** 2015-11-17 end *********/
                	 }
				} 
                catch (Exception e) 
                {
					// TODO: handle exception
                	e.printStackTrace();
				} 
            }    
        }   
        catch (Exception e)   
        {    
            e.printStackTrace();    
        }   
        finally  
        {  
            try   
            {  
                if(serverSocket != null)  
                {  
                    serverSocket.close();  
                }  
            }   
            catch (Exception e)//原来是IOException  
            {  
                e.printStackTrace();  
            }  
        }
	}	
}

/**
 * 本类处理 java端发送到本地 PHP端的消息，发送格式JSON
 * @param deviceNum: 发送过来的设备编号，将设备编号发往PHP端处理
 * @param command: 发送到PHP端的命令
 * @return
 */
class OutputlocalInfoProcess implements Runnable
{
	public Socket local;
	public Integer deviceNum;
	public String command;
	public String filepath;
	public JSONObject localInfo;
	
	public OutputlocalInfoProcess(Socket local, JSONObject localInfo) {
		// TODO Auto-generated constructor stub
		this.local = local;
		try 
		{
			System.out.println("local:1");
			this.deviceNum = localInfo.getInt("deviceNum");
			this.command = localInfo.getString("command");
			
			this.localInfo = localInfo;
			
//			if(command.equals("0x27"))
//			{
//				filepath = localInfo.getString("filename");
//			}
		} 
		catch (NumberFormatException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

	@Override
	public void run() {
		// TODO Auto-generated method stub
		Socket client = ServerOutput.clientList.get(0);
		try 
		{
			DataInputStream dis = new DataInputStream(client.getInputStream());
			DataOutputStream dos = new DataOutputStream(client.getOutputStream());
			
			/**********/
			/* 测试专用 */
//			JSONObject jsonInfo = new JSONObject();
//			jsonInfo.put("deviceNum", deviceNum);
//			jsonInfo.put("command", command);
//			jsonInfo.put("filename", filepath);
			/**********/
			
			dos.write(localInfo.toString().getBytes());
			
			/********************/
			/* 测试专用，非json格式时 */
//			dos.write(("deviceNum:"+deviceNum+"|command:"+command).getBytes());
			/********************/
			
			/****************/
			/* 测试专用，显示信息 */
//			System.out.println(deviceNum + ":" + command);
			/****************/
		} 
		catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
//		catch (JSONException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
	}
}

/**
 * 本类处理发送到远程设备的消息
 * @param deviceNum: 要发送的设备编号，通过此设备编号寻找发往对应连接的socket
 * @param command: 发往设备的命令
 * @return
 */
class OutputdeviceInfoProcess implements Runnable
{
	public Socket device;
	public Integer deviceNum;
	public String command;
	public JSONObject deviceInfo;
	
	public OutputdeviceInfoProcess(Socket device, JSONObject deviceInfo) {
		// TODO Auto-generated constructor stub
		if (deviceInfo == null) {
			System.out.println("deviceInfo == null");
			return;
		}
		
		this.device = device;
		this.deviceInfo = deviceInfo;
		try 
		{
			this.deviceNum = deviceInfo.getInt("deviceNum");
			this.command = deviceInfo.getString("command");
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}

	@Override
	public void run() {
		// TODO Auto-generated method stub
		
		/* 此处先检测玩具是否上线 */
		if( (!(ServerOutput.clientList.containsKey(deviceNum))) || (!(ServerOutput.clientList.get(deviceNum).isConnected())))
		{
			System.out.println("device "+deviceNum+" offline");
			return;
		}
		/* 这里使用发送紧急字节来检测远端是否上线 */
		try {
			ServerOutput.clientList.get(deviceNum).sendUrgentData(0XFF);
		} catch (IOException e) {
			// TODO: handle exception
			try {
				Thread.sleep(3000);
				ServerOutput.clientList.get(deviceNum).sendUrgentData(0XFF);
			} catch (IOException e1) {
				// TODO Auto-generated catch block				
				e1.printStackTrace();
				System.out.println("device "+deviceNum+" offline");
				return;
			} catch (InterruptedException e1) {
				// TODO Auto-generated catch block
				e1.printStackTrace();				
			}
			
		}
		
		/* 若玩具已上线 */
		Socket client = ServerOutput.clientList.get(deviceNum);
		try 
		{
			DataInputStream dis = new DataInputStream(client.getInputStream());
			DataOutputStream dos = new DataOutputStream(client.getOutputStream());
			
			Thread.sleep(5000);
			
//			JSONObject jsonInfo = new JSONObject();
//			jsonInfo.put("id", deviceNum);
//			jsonInfo.put("command", command);
//			
//			System.out.println(jsonInfo.toString());
			
			/* 检测玩具是否空闲 */
			JSONObject check = new JSONObject();
			JSONObject temp;
			byte[] buffer = new byte[1024];
			check.put("id", deviceNum);
			check.put("command", "0xff");
			dos.write(check.toString().getBytes());
			dos.flush();
			while (dis.read(buffer) != -1) {
				temp = new JSONObject(new String(buffer));
				System.out.println("---------------  test state  ----------");
				System.out.println(new String(buffer).trim());
				System.out.println("---------------  test state end ----------");
				if(temp.get("command").equals("0x23")){
					Thread.sleep(3000);					
					dos.write(check.toString().getBytes());
					dos.flush();
					continue;
				}
				if(temp.get("command").equals("0x21"))
				{
					break;
				}
			}
			
			/* 此处根据相应的command，进行相应处理 */
			if(command.equals("0x01") || command.equals("0x03") ||
				command.equals("0x11") || command.equals("0x05") || 
				command.equals("0x07") || command.equals("0x09") || 
				command.equals("0x0b") || command.equals("0x13") || 
				command.equals("0x2d") || command.equals("0x0d") || 
				command.equals("0x0f") || command.equals("0x85") ||
				command.equals("0x87") || command.equals("0x89") ||
				command.equals("0x8b"))
			{
				System.out.println("deviceInfo == "+deviceInfo.toString());
				dos.write(deviceInfo.toString().getBytes());
				Thread.sleep(1000);
				client.shutdownOutput();
				Thread.sleep(500);
				client.close();
			}
			
			/* 0x2f: 文字转语音 */
			if(command.equals("0x2f"))
			{
				String content = URLDecoder.decode(deviceInfo.getString("content"), "utf-8");
				System.out.println(content);
				deviceInfo.remove("content");
				deviceInfo.put("content", content);
				System.out.println(deviceInfo.toString());
				dos.write(deviceInfo.toString().getBytes());
				Thread.sleep(1000);
				client.shutdownOutput();
				client.close();
			}
			
			/* 0x1f: 语音留言 */
			if(command.equals("0x1f"))
			{
				String filepath = deviceInfo.getString("filename");
				String filename = filepath.split("/")[filepath.split("/").length -1];
				deviceInfo.remove("filename");
				deviceInfo.put("filename", filename);
				dos.write(deviceInfo.toString().getBytes());
				dis.read();
				
				FileInputStream voiceFile = new FileInputStream(new File(filepath));
				buffer = new byte[1024];
				int num = voiceFile.read(buffer);
				while(num != -1)
				{
					dos.write(buffer, 0, num);
					dos.flush();
					buffer = new byte[1024];
					num = voiceFile.read(buffer);
				}
				Thread.sleep(3000);
				client.shutdownOutput();
				Thread.sleep(1000);
				client.close();
				voiceFile.close();
			}
			
			/* 0x17: 下载故事 	0x19: 下载儿歌 		0x19: 下载经典名曲 */
			if(command.equals("0x17") || command.equals("0x19") || 
				command.equals("0x1b"))
			{
				String filepath = deviceInfo.getString("filename");
				filepath = URLDecoder.decode(filepath, "utf-8");
				String filename = filepath.split("/")[filepath.split("/").length -1];
				System.out.println(filepath);
				System.out.println(filename);
				deviceInfo.remove("filename");
				deviceInfo.put("filename", filename);
				dos.write(deviceInfo.toString().getBytes());
				dis.read();
				
				FileInputStream songFile = new FileInputStream(new File(filepath));
				buffer = new byte[1024];
				int num = songFile.read(buffer);
				while(num != -1)
				{
					dos.write(buffer, 0, num);
					dos.flush();
					buffer = new byte[1024];
					num = songFile.read(buffer);
				}
				Thread.sleep(5000);
				client.shutdownOutput();
				Thread.sleep(3000);
				client.close();
				System.out.println("------------- file send over -------------");
				songFile.close();
			}
			
			/* 0x1d: 下载广播列表 */
			if(command.equals("0x1d"))
			{
				String filepath = deviceInfo.getString("filename");
				filepath = URLDecoder.decode(filepath, "utf-8");
				String filename = filepath.split("/")[filepath.split("/").length -1];
				System.out.println(filepath);
				System.out.println(filename);
				deviceInfo.remove("filename");
				deviceInfo.put("filename", filename);
				dos.write(deviceInfo.toString().getBytes());
				dis.read();
				
				FileInputStream broadcastFile = new FileInputStream(new File(filepath));
				buffer = new byte[1024];
				int num = broadcastFile.read(buffer);
				while(num != -1)
				{
					dos.write(buffer, 0, num);
					dos.flush();
					buffer = new byte[1024];
					num = broadcastFile.read(buffer);
				}
				Thread.sleep(2000);
				client.shutdownOutput();
				Thread.sleep(1000);
				client.close();
				System.out.println("------------- file send over -------------");
				broadcastFile.close();
			}
			
			/* 0x31: 删除歌曲 	0x33: 删除故事 		0x35: 删除经典名曲  */
			if(command.equals("0x31") || command.equals("0x33") || 
				command.equals("0x35"))
			{
				String filename = URLDecoder.decode(deviceInfo.getString("filename"), "utf-8");
				System.out.println(filename);
				deviceInfo.remove("filename");
				deviceInfo.put("filename", filename);
				System.out.println(deviceInfo.toString());
				dos.write(deviceInfo.toString().getBytes());
				Thread.sleep(500);
				client.shutdownOutput();
				Thread.sleep(500);
				client.close();
			}
			
			/* 0x51: 下载个人资源 */
			if(command.equals("0x51"))
			{
				String filepath = deviceInfo.getString("filename");
				filepath = URLDecoder.decode(filepath, "utf-8");
				String filename = filepath.split("/")[filepath.split("/").length -1];
				System.out.println(filepath);
				System.out.println(filename);
				deviceInfo.remove("filename");
				deviceInfo.put("filename", filename);
				dos.write(deviceInfo.toString().getBytes());
				dis.read();
				
				FileInputStream personalFile = new FileInputStream(new File(filepath));
				buffer = new byte[1024];
				int num = personalFile.read(buffer);
				while(num != -1)
				{
					dos.write(buffer, 0, num);
					dos.flush();
					buffer = new byte[1024];
					num = personalFile.read(buffer);
				}
				Thread.sleep(5000);
				client.shutdownOutput();
				Thread.sleep(2000);
				client.close();
				System.out.println("------------- file send over -------------");
				personalFile.close();
			}
			
			System.out.println("------------- connect send over -------------");
		} 
		catch (IOException e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
			return;
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}

/**
 * 此类是主要用于socket输入流连接
 * 此链接不需要长连接，只有当客户端有需要传输信息时才连接
 */
class ServerInput implements Runnable
{

    public static final int PORT = 8001;//监听的端口号  
	
	public void run() {
		// TODO Auto-generated method stub
		ServerSocket serverSocket = null;//定义一个ServerSocket  
        try   
        {    
            serverSocket = new ServerSocket(PORT);//新建一个ServerSocket对象    
            while (true)   
            {   
            	//不断等待客户端连接   
                Socket client = serverSocket.accept();
                  
                //客户端连接后，首先读取客户端发来的客户号
                try 
                {
                	 DataInputStream dis = new DataInputStream(client.getInputStream());
                	 DataOutputStream dos = new DataOutputStream(client.getOutputStream());
                	 byte[] buffer = new byte[1024];
                	 dis.read(buffer);
                	 System.out.println(new String(buffer).trim());
                	 JSONObject info = new JSONObject(new String(buffer, "utf-8").trim());
                	 Integer clientNum = Integer.parseInt(info.getString("id"));
//                	 ServerInput.clientList.put(clientNum, client);
                	 System.out.println("Input: 客户端 " + clientNum + " 连接成功");
                	 dos.write("ok".getBytes());
                	 
                	 /* 0 是本机PHP程序的客户号，此处进行相应的函数处理 */
                	 if(clientNum == 0)
                	 {
                     	 new Thread(new InputLocalInfoProcess(client)).start();
                	 }
                	 
                	 /* 终端发送的消息，此处进行相应的函数处理 */
                	 else 
                	 {
                		 new Thread(new InputDeviceInfoProcess(client, clientNum)).start();
                	 }
				} 
                catch (Exception e) 
                {
					// TODO: handle exception
                	e.printStackTrace();
				} 
            }    
        }   
        catch (Exception e)   
        {    
            e.printStackTrace();    
        }   
        finally  
        {  
            try   
            {  
                if(serverSocket != null)  
                {  
                    serverSocket.close();  
                }  
            }   
            catch (Exception e) 
            {  
                e.printStackTrace();  
            }  
        }
	}
	
}

/**
 * 本类处理 本地 PHP端发送过来的消息，并通过输出流方法通知远程设备进行处理
 * @param deviceNum: 本地的PHP端连接
 * @param command: 发送到PHP端的命令
 * @return
 */
class InputLocalInfoProcess implements Runnable
{
	private Socket local;
	
	public InputLocalInfoProcess(Socket local) {
		// TODO Auto-generated constructor stub
		this.local = local;
	}

	@Override
	public void run() {
		// TODO Auto-generated method stub
		try 
		{
			DataInputStream dis = new DataInputStream(local.getInputStream());
			DataOutputStream dos = new DataOutputStream(local.getOutputStream());
			byte[] buffer = new byte[1024];
			
			// 接受本地端口的指令
			dis.read(buffer);
			String info = (new String(buffer, "utf-8")).trim();
			System.out.println(info);
			JSONObject jsonInfo = new JSONObject(info); // 本地PHP发送过来的指令			
			Integer deviceNum = new Integer(jsonInfo.getString("deviceNum"));

			JSONObject outputDeviceInfo = new JSONObject();	// 发送到 OutputdeviceInfoProcess 的指令		

			outputDeviceInfo = process(jsonInfo);
			
			/********************/
			/* 测试专用，非JSON格式时 */
//			Integer deviceNum = Integer.parseInt((info.split(":")[0]));
//			String command = info.split(":")[1];
//			System.out.println(info);
			/********************/
			
			/* 使用输出流通知相应设备进行相应处理  */
//			new Thread(new OutputdeviceInfoProcess(ServerOutput.clientList.get(deviceNum), outputDeviceInfo)).start();
			if(CommandQueue.commandQueue == null)
			{
				System.out.println("CommandQueue.commandQueue == null");
			}
			if(CommandQueue.commandQueue.get(deviceNum) == null)
			{
				CommandQueue.commandQueue.put(deviceNum, new LinkedList<JSONObject>());
				System.out.println("CommandQueue.commandQueue.get(deviceNum) == null");
				new Thread(new CommandQueue(deviceNum)).start();
			}
			if(outputDeviceInfo == null)
			{
				System.out.println("outputDeviceInfo == null");
			}
			CommandQueue.commandQueue.get(deviceNum).add(outputDeviceInfo);
			/**************************************/
//			if((!CommandQueue.commandQueue.containsKey(deviceNum)) || (CommandQueue.commandQueue.get(deviceNum)==null))
//			{
//				System.out.println("!commandQueue");
//				LinkedList<JSONObject> cmdque = new LinkedList<JSONObject>();
//				CommandQueue.commandQueue.put(deviceNum, cmdque);
//			}
//			LinkedList<JSONObject> abc = CommandQueue.commandQueue.get(deviceNum);
//			if(abc == null)
//			{
//				System.out.println("==============================");
//				System.out.println("abc == null");
//				System.out.println("==============================");
//				abc = new LinkedList<>();
//				CommandQueue.commandQueue.put(deviceNum, abc);
//			}
//			if ( (CommandQueue.commandQueue.get(deviceNum).isEmpty()) && 
//					(CommandQueue.processFlag.get(deviceNum) == false) ) {
//				System.out.println("new commandQueue");
//				CommandQueue.commandQueue.get(deviceNum).add(outputDeviceInfo);
//				new Thread(new CommandQueue(deviceNum)).start();
//			}
			
//			if( (!CommandQueue.commandQueue.get(deviceNum).isEmpty()) ||
//					(CommandQueue.processFlag.get(deviceNum) == true) ) {
//				System.out.println("add commandQueue");
//				CommandQueue.commandQueue.get(deviceNum).add(outputDeviceInfo);
//			}
			/**************************************/
		} 
		catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public JSONObject process(JSONObject jsonInfo)
	{
		JSONObject processJSONInfo = new JSONObject();
		try 
		{
			Integer deviceNum = jsonInfo.getInt("deviceNum");
			String command = jsonInfo.getString("command");
			
			if(command.equals("story"))
			{
				command = "0x05";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("song"))
			{
				command = "0x07";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("music"))
			{
				command = "0x09";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("broadcast"))
			{
				command = "0x0b";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("radio_num", jsonInfo.getString("radioNum"));
			}
			if(command.equals("take_photo"))
			{
				command = "0x11";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("leave_message"))
			{
				command = "0x1f";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("video"))
			{
				command = "0x13";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("set_volume_4"))
			{
				command = "0x2d";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("volume_up"))
			{
				command = "0x0d";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("volume_down"))
			{
				command = "0x0f";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("content"))
			{
				command = "0x2f";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("content", jsonInfo.getString("content"));
			}
			if(command.equals("download_story"))
			{
				command = "0x17";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("download_song"))
			{
				command = "0x19";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("download_music"))
			{
				command = "0x1b";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("download_radiolist"))
			{
				command = "0x1d";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("delete_song"))
			{
				command = "0x31";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("delete_story"))
			{
				command = "0x33";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("delete_music"))
			{
				command = "0x35";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			if(command.equals("previous"))
			{
				command = "0x01";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("next"))
			{
				command = "0x03";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("pause_story"))
			{
				command = "0x85";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("pause_song"))
			{
				command = "0x87";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("pause_music"))
			{
				command = "0x89";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("pause_broadcast"))
			{
				command = "0x8b";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
			}
			if(command.equals("push_resource"))
			{
				command = "0x51";
				processJSONInfo.put("deviceNum", deviceNum);
				processJSONInfo.put("command", command);
				processJSONInfo.put("filename", jsonInfo.getString("filename"));
			}
			
		} 
		catch (JSONException e) 
		{
				// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		return processJSONInfo;
	}
}


/**
 * 本类处理远程设备发送过来的消息，并通过输出流方法通知PHP端进行处理
 * @param device: 发送过来的设备的socket连接
 * @param deviceNum: 发往设备的编号
 */
class InputDeviceInfoProcess implements Runnable
{
	private Socket device;
	private Integer deviceNum;
	private static final String preFileName = "/var/www/html/ljc/resource/device/";
	private JSONObject localInfo;
	
	public InputDeviceInfoProcess(Socket device, Integer deviceNum) {
		// TODO Auto-generated constructor stub
		this.device = device;
		this.deviceNum = deviceNum;
		localInfo = new JSONObject();
	}

	@Override
	public void run() {
		// TODO Auto-generated method stub
		String command = "0x00";
		try 
		{
			/*****************/
			/* 测试专用，键盘输入流 */
//			DataInputStream dis = new DataInputStream(System.in);
			/****************/
			DataInputStream dis = new DataInputStream(device.getInputStream());
			DataOutputStream dos = new DataOutputStream(device.getOutputStream());
			byte[] buffer = new byte[1024];
			dis.read(buffer);
			System.out.println("3");
			String info = new String(buffer, "utf-8").trim();
			buffer = new byte[1024];
			JSONObject jsonInfo = new JSONObject(info);
			deviceNum = Integer.parseInt(jsonInfo.getString("id"));
			command = jsonInfo.getString("command");
			/********************/
			/* 测试专用，非JSON格式时 */
//			deviceNum = Integer.parseInt(info.split(":")[0]);
//			String command = info.split(":")[1];
			/********************/
			dos.write("ok".getBytes());
			System.out.println("4");
			
			/* 此处根据相应的command，进行相应处理 */
//			JSONObject localInfo = new JSONObject();
			localInfo.put("deviceNum", deviceNum);
			localInfo.put("command", command);
			
			process(jsonInfo);
			
			/* 处理完后，使用输出流通知本地进行相应处理 */
			/****************/
			/* 测试专用，显示信息 */
//			System.out.println(jsonInfo.toString());
			/****************/
			
			new Thread(new OutputlocalInfoProcess(ServerOutput.clientList.get(0), localInfo)).start();
		} 
		catch (IOException e) {
			// TODO Auto-generated catch block
			if(command.equals("0x27") || command.equals("0x2b") || command.equals("0x29"))
			{
				new Thread(new OutputlocalInfoProcess(ServerOutput.clientList.get(0), localInfo)).start();
			}
			e.printStackTrace();
		} 
		catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	public void process(JSONObject jsonInfo) throws IOException
	{
		try 
		{
			DataInputStream dis = new DataInputStream(device.getInputStream());
			DataOutputStream dos = new DataOutputStream(device.getOutputStream());
			String command = jsonInfo.getString("command");
			byte[] buffer = new byte[1024];
			
			/* 0x27:接收玩具传输过来的图片文件 */
			if(command.equals("0x27"))
			{
				File filePath = new File(preFileName + deviceNum);
				if(!(filePath.isDirectory()))
				{
					filePath.mkdir();
				}
				
				/* 解析获得文件名 */
				String filename = jsonInfo.getString("filename").trim();		
				File file = new File(filePath, filename);
				file.createNewFile();
				RandomAccessFile rf = new RandomAccessFile(file, "rw");
				buffer = new byte[1024];
//				dos.write("ok".getBytes());
				System.out.println("6");
				
				localInfo.put("filename", file.getAbsolutePath());
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
	//			dos.write("ok".getBytes());
				Thread.sleep(1000);
				rf.close();
				dos.write("ok".getBytes());
//				Thread.sleep(1000);
//				device.shutdownInput();
				Thread.sleep(500);
				device.close();
			}
			
			/* 0x2b:接收玩具传输过来的录音文件 */
			if(command.equals("0x2b"))
			{
				File filePath = new File(preFileName + deviceNum);
				if(!(filePath.isDirectory()))
				{
					filePath.mkdir();
				}
				
				/* 解析获得文件名 */
				String filename = jsonInfo.getString("filename").trim();		
				File file = new File(filePath, filename);
				file.createNewFile();
				RandomAccessFile rf = new RandomAccessFile(file, "rw");
				buffer = new byte[1024];
//				dos.write("ok".getBytes());
				System.out.println("6");
				
				localInfo.put("filename", file.getAbsolutePath());
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
				Thread.sleep(1000);
				rf.close();
				dos.write("ok".getBytes());
//				Thread.sleep(1000);
//				device.shutdownInput();
				Thread.sleep(500);
				device.close();
			}
			
			/* 0x29:接收玩具传输过来的视频文件 */
			if(command.equals("0x29"))
			{
				File filePath = new File(preFileName + deviceNum);
				if(!(filePath.isDirectory()))
				{
					filePath.mkdir();
				}
				
				/* 解析获得文件名 */
				String filename = jsonInfo.getString("filename").trim();		
				File file = new File(filePath, filename);
				file.createNewFile();
				RandomAccessFile rf = new RandomAccessFile(file, "rw");
				buffer = new byte[1024];
//				dos.write("ok".getBytes());
				System.out.println("6");
				
				localInfo.put("filename", file.getAbsolutePath());
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
	//			dos.write("ok".getBytes());
				Thread.sleep(1000);
				rf.close();
//				Thread.sleep(2000);
				dos.write("ok".getBytes());
				Thread.sleep(500);
				rf.close();
			}
			
			/* 0x15:接收玩具传输过来的歌曲列表文件 */
			if(command.equals("0x61"))
			{
				File songlist = new File(preFileName + deviceNum + "/songlist.txt");
				if(songlist.exists())
				{
					songlist.delete();
				}
				songlist.createNewFile();
				
				System.out.println(songlist.getAbsolutePath());
				RandomAccessFile rf = new RandomAccessFile(songlist, "rw");
				buffer = new byte[1024];
				dos.write("ok".getBytes());
				System.out.println("6");
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
				Thread.sleep(1000);
				rf.close();
//				device.shutdownInput();
				dos.write("ok".getBytes());
				Thread.sleep(500);
				device.close();
			}
			
			/* 0x63:接收玩具传输过来的故事列表文件 */
			if(command.equals("0x63"))
			{
				File songlist = new File(preFileName + deviceNum + "/storylist.txt");
				if(songlist.exists())
				{
					songlist.delete();
				}
				songlist.createNewFile();
				
				System.out.println(songlist.getAbsolutePath());
				RandomAccessFile rf = new RandomAccessFile(songlist, "rw");
				buffer = new byte[1024];
				dos.write("ok".getBytes());
				System.out.println("6");
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
				Thread.sleep(1000);
				rf.close();
				dos.write("ok".getBytes());
//				device.shutdownInput();
				Thread.sleep(500);
				device.close();
			}
			
			/* 0x65:接收玩具传输过来的经典名曲列表文件 */
			if(command.equals("0x65"))
			{
				File songlist = new File(preFileName + deviceNum + "/musiclist.txt");
				if(songlist.exists())
				{
					songlist.delete();
				}
				songlist.createNewFile();
				
				System.out.println(songlist.getAbsolutePath());
				RandomAccessFile rf = new RandomAccessFile(songlist, "rw");
				buffer = new byte[1024];
				dos.write("ok".getBytes());
				System.out.println("6");
				
				/*开始下载文件*/				
				int num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数			
				while(num != -1)//如果因为已经到达文件末尾而没有更多的数据，则返回 -1
				{
					rf.write(buffer, 0, num);//将buf数组中从0 开始的num个字节写入此文件输出流
					buffer = new byte[1024];
					rf.skipBytes(num);//跳过num个字节数
					num = dis.read(buffer);//从此输入流中将最多 buf.length个字节的数据读入一个 buf数组中。返回：读入缓冲区的字节总数
				}
				Thread.sleep(1000);
				rf.close();
//				device.shutdownInput();
				dos.write("ok".getBytes());
				Thread.sleep(500);
				device.close();
			}
		} 
		catch (JSONException e) 
		{
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}

/**
 * 命令队列方法的实现
 * @param deviceId: 设备ID
 * @param processFlag: 设备处理标志位，当设备每次连接服务器时，自动将此标志位设为false
 * @param deviceCommandQueue: 设备命令处理队列，将对应命令存入该设备的命令队列中
 *
 */
class CommandQueue implements Runnable
{

	public Integer deviceId;
	public static HashMap<Integer, Boolean> processFlag = new HashMap<Integer, Boolean>();
	public static HashMap<Integer, LinkedList<JSONObject>> commandQueue = new HashMap<Integer, LinkedList<JSONObject>>();
	
	public CommandQueue()
	{}
	
	public CommandQueue(Integer deviceId) {
		// TODO Auto-generated constructor stub
		this.deviceId = deviceId;
	}
	
	@Override
	public void run() {
		int i = 1;
		// TODO Auto-generated method stub
		while (true)
		{
//			if (CommandQueue.processFlag.get(deviceId) == false) {
				if (CommandQueue.commandQueue.get(deviceId).peek() != null) {
					if (CommandQueue.processFlag.get(deviceId) == false) {
						CommandQueue.processFlag.remove(deviceId);
						CommandQueue.processFlag.put(deviceId, true);
						i = 1;
						System.out.println("commandQueue start");
						new Thread(new OutputdeviceInfoProcess(ServerOutput.clientList.get(deviceId), CommandQueue.commandQueue.get(deviceId).poll())).start();
					}
					if (CommandQueue.processFlag.get(deviceId) == true) {
						System.out.println("commandQueue wait");
						try {
							if (i <= 5)
							{
								System.out.println("Thread sleep ");
								Thread.sleep(10000);
								i++;
							}
							else {
								System.out.println("remove command queue "+deviceId);
								CommandQueue.commandQueue.put(deviceId, new LinkedList<JSONObject>());
//								break;
							}					
						} catch (InterruptedException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
					}
				}
//			}
		}
	}
	
}

/** 
 * 使用 UDP 来检测在线情况，客户端每30秒发一个时间戳过来
 * @author Jacskon
 *
 */
class OnlineServer implements Runnable
{
	public static HashMap<Integer, Long> onlineTime = new HashMap<Integer, Long>();
	public static final int PORT = 8002;
	public DatagramSocket ds;
	
	public OnlineServer() {
		// TODO Auto-generated constructor stub
		try {
			ds = new DatagramSocket(PORT);
		} catch (SocketException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	@Override
	public void run() {
		// TODO Auto-generated method stub
		while (true) {
			byte[] buffer = new byte[1024];
			DatagramPacket dp = new DatagramPacket(buffer, buffer.length);
			try {
				ds.receive(dp);
				
				/* 此处对获得的包进行解析 */
				System.out.println("raw data:"+new String(dp.getData(), 0, dp.getLength()));
				JSONObject info = new JSONObject(new String(dp.getData()).trim());
				System.out.println("info data:"+info.toString());
				Integer deviceId = Integer.parseInt((String)info.getString("id"));
				Long time = Long.parseLong((String)info.getString("time"));
				if(!ServerOutput.onlineFlag.containsKey(deviceId))
				{
					ServerOutput.onlineFlag.put(deviceId, false);
				}
				if(ServerOutput.onlineFlag.get(deviceId) == false)
				{
					ServerOutput.onlineFlag.put(deviceId, true);
					JSONObject onlineInfo = new JSONObject();
					onlineInfo.put("deviceNum", deviceId);
					onlineInfo.put("command", "0x33");
					new Thread(new OutputlocalInfoProcess(ServerOutput.clientList.get(0), onlineInfo)).start();
				}
				OnlineServer.onlineTime.put(deviceId, time);
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
}

/**
 * 此类用于检测离线终端
 * 客户端每隔30秒发送一个时间戳，若检测的时间戳与上一次收到的时间戳相隔超过300秒，则判定设备离线
 * @author Jacskon
 *
 */
class TestOnline implements Runnable
{

	@Override
	public void run() {
		// TODO Auto-generated method stub
		while (true) {
			HashMap<Integer, Long> online = new HashMap<>();
			online.putAll(OnlineServer.onlineTime);
			Date date = new Date();
			long millTime = date.getTime();
			Iterator<Integer> it = online.keySet().iterator();
			while (it.hasNext()) {
				Integer deviceId = (Integer) it.next();
				if(ServerOutput.onlineFlag.get(deviceId) == true){
					if(millTime - online.get(deviceId).longValue() > 300000){
						/* 此处对玩具离线进行处理 */
						ServerOutput.onlineFlag.put(deviceId, false);
						JSONObject offlineInfo = new JSONObject();
						try {
							offlineInfo.put("deviceNum", deviceId);
							offlineInfo.put("command", "0x35");
							new Thread(new OutputlocalInfoProcess(ServerOutput.clientList.get(0), offlineInfo)).start();
						} catch (JSONException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
						
					}
				}
			}
			try {
				Thread.sleep(180000);
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
	}
}
