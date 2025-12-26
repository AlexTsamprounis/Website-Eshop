console.log("Test2.js loaded");
console.log("This is a test file for TEST2.html");
let Name = "John";
let lastName = "Doe";
let age = 30;
let isActive = true;
let notDefined;
console.log("User:", Name, lastName);
function HelloWorld()
{
    console.log("Hello, World!");
}
console.log(HelloWorld());

let bags = ["Backpack", "Purse", "Suitcase", "Duffel Bag", "Tote Bag"];
let colors = ["Red", "Navyblue", "Green", "Black", "White", "Yellow", "Cappuccino", "Taupe", "Gray", "Brown"];
let str = "My name is " + Name + " " + lastName + " and I am " + age + " years old.";
console.log(str);

let float = Math.PI;
console.log("The value of PI is approximately:", float.toFixed(2));

const numbers = [10, 20, 30, 40, 50];
const arrays = ["Alex", "Sam", "Charlie", "Jordan", "Taylor"];

let arr;

arr = numbers.concat(arrays);
arr = [...numbers, ...arrays];  /* einai to idio */
arr = numbers.length;
arr = Array.isArray(numbers);

console.log(arr);

/* OBJECT LITERALS */

const person = 
{
    Name: "Alice",
    lastName: "Smith",
    age: 25,
    city: "New York",
    isStudent: true,
    lessons: ["Math", "English", "Biology", "Geography"],
    address: 
    {
        street: "123 Main St",
        zip: "10001",                
        city: "New York",           
    }            
};

console.log(person);

/* END OBJECT LITERALS */

/*DATE AND REGULAR EXPRESSIONS START*/

let today = new Date();
let birthday = new Date(1992, 3, 29); //month is 0-indexed
console.log("Today is:", today.toDateString());
console.log("Birthday is:", birthday.toDateString());

/*DATE AND REGULAR EXPRESSIONS END*/

/* IF STATEMENTS START */

let x = 1;

if(x === 2) {
    console.log("first path");
    }
else
    {
    console.log("second path");
};

/* IF STATEMENTS END */

/* FUNCTIONS START */

function SayHi(name,age){
    return `Hi, my name is ${name} and I am ${age} years old.`;
}
console.log(SayHi("Alex", 33));
console.log(SayHi("Bob", 28));

/* function expression */
const square = function(x){
    return x * x;
}
console.log(square(5));

/* arrow function */
const addTwo = (x) =>{
    return x + 2;
}
console.log(addTwo(5));

/* FUNCTIONS END */

/* array methods START */

const laguages = ["JavaScript", "Python", "Java", "C#", "Ruby"];
const Numbers = [1, 2, 3, 5, 8, 13, 21];

// forEach()

let items = "";

laguages.forEach((laguage, index) => {
    items += `<li>${index + 1}. ${laguage}</li>`;
});

console.log(`<ul>${items}</ul>`); 

// map()

const upperLaguages = laguages.map(laguage => laguage.toUpperCase());
console.log(upperLaguages);

const numberofLetetrs = laguages.map((language, index, array) => {
    return {
        [index]: language.length,
    };
});

console.log(numberofLetetrs);

// filter()/find()

const aboveFive = Numbers.filter(number => number > 5);
console.log(aboveFive);

/*array methods END */

/* LOOPS START */

// for in loop

const client = {
    name: "Emma",
    age: 29,
    city: "Los Angeles",
};

for (let property in client) {
    console.log(`${property}: ${client[property]}`);
}

// for of loop
const languages = ["JavaScript", "Python", "Java", "C#", "Ruby"];

for (let language of languages) {
    console.log(language);
}

/* LOOPS END */

console.log(window);
console.log(console === window.console);

//σελεκτορες
const header = document.getElementById("header");
console.log(header);

const itemsList = document.getElementsByClassName("item");
console.log(itemsList);

const paragraphs = document.getElementsByTagName("p");
console.log(paragraphs);

const firstItem = document.querySelector(".item");
console.log(firstItem);

const allItems = document.querySelectorAll(".item");
console.log(allItems);

// const h3 = document.querySelectorAll("h3");
// h3.forEach(element => {
//     element.style.color = "red";
// });

/* -------- Creating and inserting elements -------- */ 

// document.body.innerHTML = ""; // Clear existing content

/* const title = document.createElement("h2");
title.textContent = "This is a dynamically created title";
document.body.appendChild(title);   

const paragraph = document.createElement("p");
paragraph.textContent = "This is a dynamically created paragraph";
document.body.appendChild(paragraph); */

/* -------- End Creating and inserting elements -------- */

// add class (last-section) to last section

/* const sections = document.querySelectorAll("section");
sections.forEach((section, index) => {
    if (index === sections.length - 1) {
        section.classList.add("last-section");
    }
}); */

/* -------- Event Listeners start -------- */ 

const button = document.querySelector("myButton");
button.addEventListener("click", function(e) {
    console.log("Button clicked!");
    console.log(e);
});

let val; 
val = e;

val = e.target;
val = e.target.id;
val = e.target.className;
val = e.target.classList;
val = e.type;
val = e.timeStamp;
val = e.clientY;
val = e.clientX;

console.log(val);

// mouse events
const myButton = document.getElementById("myButton");
myButton.addEventListener("mousedown", function(e) {
    console.log("Mouse down!");
});
myButton.addEventListener("mouseup", function(e) {
    console.log("Mouse up!");
});
myButton.addEventListener("mouseenter", function(e) {
    console.log("Mouse enter!");
});
myButton.addEventListener("mouseleave", function(e) {
    console.log("Mouse leave!");
});
myButton.addEventListener("mouseover", function(e) {
    console.log("Mouse over!");
});
myButton.addEventListener("mouseout", function(e) {
    console.log("Mouse out!");
});

// form events ----------------------------------------

const form = document.getElementById("myForm");
form.addEventListener("submit", function(e) {
    e.preventDefault();
    console.log("Form submitted!");
});

form.addEventListener("reset", function(e) {
    console.log("Form reset!");
});

// key events ----------------------------------------

const input = document.querySelector("myInput");

input.addEventListener("keydown", function(e) {
    console.log("Key down!");
});

input.addEventListener("keyup", function(e) {
    console.log("Key up!");
});

input.addEventListener("keypress", function(e) {
    console.log("Key press!");
});

input.addEventListener("mousemove", function(e) {
    console.log("Mouse move!");
});

// form input events ----------------------------------------

input.addEventListener("focus", function(e) {
    console.log("Input focus!");
});

input.addEventListener("blur", function(e) {
    console.log("Input blur!");
});

input.addEventListener("cut", function(e) {
    console.log("Input cut!");
});

input.addEventListener("copy", function(e) {
    console.log("Input copy!");
});

input.addEventListener("paste", function(e) {
    console.log("Input paste!");
});

/* -------- End Event Listeners -------- */