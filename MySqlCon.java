import java.sql.*;

class MysqlCon {
    public static void main(String args[]) {
        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection(
                    "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr:3306/furkan_ahi", "furkan.ahi", "Cz0ZYTaO");
            System.out.println("Connected database successfully...\n");
            Statement stmt = con.createStatement();
            System.out.println("Removing previous tables if exists...");
            stmt.execute("DROP TABLE IF EXISTS apply, company, student");
            System.out.println("Removed previous tables...\n");

            System.out.println("Starting to create tables...");
            stmt.execute("create table student(sid CHAR(12), sname VARCHAR(50), bdate DATE, address VARCHAR(50)," +
                    "scity  VARCHAR(20), year  CHAR(20), gpa  FLOAT, nationality VARCHAR(20), primary key(sid)) ENGINE= InnoDB;\n");
            stmt.execute("create table company(cid CHAR(8), cname VARCHAR(20), quota INT, primary key(cid)) ENGINE= InnoDB;");
            stmt.execute("create table apply (sid char(12), cid char(8), Primary key(sid, cid)) ENGINE=InnoDB;");
            System.out.println("Tables created...\n");

            System.out.println("Starting to insert values to student table...");
            String students = "insert into student (sid, sname, bdate, address, scity, year, gpa, nationality) values" +
                    "(21000001, 'John', '1999-05-14', 'Windy', 'Chicago', 'senior', 2.33, 'US')," +
                    "(21000002, 'Ali', '2001-09-30', 'Nisantasi', 'Istanbul', 'junior', 3.26, 'TC')," +
                    "(21000003, 'Veli', '2003-02-25', 'Nisantasi', 'Istanbul', 'freshman', 2.41, 'TC')," +
                    "(21000004, 'Ayse', '2003-01-15', 'Tunali', 'Ankara', 'freshman', 2.55, 'TC');";
            stmt.execute(students);
            System.out.println("Values are inserted to student table...\n");

            System.out.println("Starting to insert values to company table...");
            String companies = "INSERT INTO company VALUES" +
                    "('C101', 'microsoft', 2)," +
                    "('C102', 'merkez bankasi', 5)," +
                    "('C103', 'tai', 3)," +
                    "('C104', 'tubitak', 5)," +
                    "('C105', 'aselsan', 3)," +
                    "('C106', 'havelsan', 4)," +
                    "('C107', 'milsoft', 2);";
            stmt.execute(companies);
            System.out.println("Values are inserted to company table...\n");


            System.out.println("Starting to insert values to apply table...");
            String applies = "INSERT INTO apply VALUES" +
                    "(21000001, 'C101')," +
                    "(21000001, 'C102')," +
                    "(21000001, 'C103')," +
                    "(21000002, 'C101')," +
                    "(21000002, 'C105')," +
                    "(21000003, 'C104')," +
                    "(21000003, 'C105')," +
                    "(21000004, 'C107');";
            stmt.execute(applies);
            System.out.println("Values are inserted to apply table...\n");

            ResultSet rs = stmt.executeQuery("SELECT * FROM student");
            System.out.println("sid \t sname \t bdate \t telno \t scity \t year \t gpa");

            while (rs.next()) {
                String sid = rs.getString("sid");
                String sname = rs.getString("sname");
                String bdate = rs.getString("bdate");
                String address = rs.getString("address");
                String scity = rs.getString("scity");
                String year = rs.getString("year");
                String gpa = rs.getString("gpa");
                String nationality = rs.getString( "nationality");
                System.out.println(sid + " " + sname + " " + bdate + " " + address + " " + scity + " " + year + " " + gpa + " " + nationality + " ");
            }
            con.close();
        } catch (Exception e) {
            System.err.println("Error Statement or Connection Failed!!!!");
            e.printStackTrace();
        }
    }
}  
