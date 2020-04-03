function sum(var1,var2){
    return var1+var2;
}

function sumofArray(numbers){
    var sum = 0;
    for (i = 0 ; i < numbers.length ; i++){
        sum += numbers[i];
    }
    return sum; 
}

function minofArray(numbers){
    var min=numbers[0];
    for (i = 0 ; i < numbers.length;i++){
        if(numbers[i]< min){
            min=numbers[i];
        }
    }
    return min;
}

function findMinIndex(numbers){
    var minIn=0;
    for(i=0;i<numbers.length;i++){
        if(numbers[minIn]>numbers[i]){
            minIn=i;
        }
    }
    return minIn;
}

function findMinIndexwithStartingPoint(numbers,start){
    var minIndex=start;
    for(i=start;i<numbers.length;i++){
        if(numbers[minIndex]>numbers[i]){
            minIndex=i;
        }
    }
    return minIndex;
}

function Selectionsort(numbers){
    var minIndex=0;
    var temp=0;
    for(var i=0;i<numbers.length-1;i++){
        minIndex=findMinIndexwithStartingPoint(numbers,i);
        temp=numbers[i];
        numbers[i]=numbers[minIndex];
        numbers[minIndex]=temp;
    }
    return numbers;
}