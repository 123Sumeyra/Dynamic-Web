var students;
function SplitNames(text, chr){

    var splitted = text.split(chr);
    return splitted;
}
function ConvertArrayToFormatedText(arr,formatStr){
    var result="";
    for(var i =0; i<arr.length;i++){
        result +=arr[i] + formatStr;
    }
    return result;
}

// class MyClass {
//     constructor(name,grade){
//         this.name =name
//         this.grade = grade
//     }
//     getDetails(){ 
//         return (`The name is ${this.name}, its grades ${this.grade}.`) 
//     } 
// }
//   // Making object with the help of the constructor 
//   let student = new MyClass('Hayabusa', 10); 
//   //document.getElementById("student").innerHTML = student.getDetails(); 
         
// getDetails()
//this.grades = new Array(grades.length);
//for(i=0; i<grades.length,i++)
//  this.grade[i]=grades[i]

class Student{
    constructor(name,grd){
        this.name =name
        this.grade = new Array(grd.length);
        for(var i=0;i<grd.length;i++){
            this.grade[i] = grd[i];
        }
    }
    Average(){
        var sum=0;
        for(var i =0; i<this.grade.length; i++){
            sum+=this.grade[i];
        }
        return sum/this.grade.length;
    }
    ToString(){
        var result = this.studentName * "";
        for(var i=0;i<this.grade.length;i++ ){
            result+=this.grade[i] + "";
        }
        return result + "AVG..:" +this.Average() +"<br>";
    }
    //document.getElementById(student).innerHTML = Student.present();


}