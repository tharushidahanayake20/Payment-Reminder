import dotenv from 'dotenv';
import mongoose from 'mongoose';
import Customer from './models/Customer.js';

dotenv.config();

// MongoDB connection
const connectDB = async () => {
  try {
    await mongoose.connect(process.env.MONGODB_URI);
    console.log('MongoDB Connected...');
  } catch (err) {
    console.error('Error connecting to MongoDB:', err.message);
    process.exit(1);
  }
};

// Get current date in DD/MM/YYYY format
const getFormattedDate = (daysOffset = 0) => {
  const date = new Date();
  date.setDate(date.getDate() + daysOffset);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}/${month}/${year}`;
};

// Sample customer data with all new fields
const sampleCustomers = [
  {
    // Basic Identity
    accountNumber: "1001234567",
    name: "Kumar Singh",
    region: "Western",
    rtom: "Colombo",
    
    // Product & Service
    productLabel: "Fiber Broadband 100Mbps",
    medium: "FIBER",
    
    // Billing
    latestBillAmount: 2500,
    newArrears: 2000,
    amountOverdue: "Rs.2000",
    daysOverdue: "16",
    nextBillDate: new Date('2025-12-01'),
    ageMonths: 24,
    
    // Contact Info
    mobileContactTel: "0704545457",
    contactNumber: "070 454 5457",
    emailAddress: "kumar.singh@email.com",
    mobileNoConfirmation: true,
    
    // Credit Info
    creditScore: 650,
    creditClassName: "Medium Risk",
    billHandlingCodeName: "Standard",
    
    // Management
    accountManager: "Saman Perera",
    salesPerson: "Nimal Fernando",
    
    // Status
    status: "PENDING",
    response: "Will Be Paid Next Week",
    previousResponse: "Said would pay last Friday",
    contactHistory: [
      {
        contactDate: getFormattedDate(0),
        outcome: "Spoke to Customer",
        remark: "Said would pay last Friday",
        crmAction: "Follow-up scheduled",
        customerFeedback: "Promised to pay",
        creditAction: "Monitor",
        retriedCount: 0,
        promisedDate: getFormattedDate(7),
        paymentMade: false,
        contactedBy: null
      }
    ],
    assignedTo: null,
    assignedDate: getFormattedDate(0)
  },
  {
    // Basic Identity
    accountNumber: "1001234568",
    name: "Ravi Kumar",
    region: "Central",
    rtom: "Kandy",
    
    // Product & Service
    productLabel: "ADSL 8Mbps",
    medium: "ADSL",
    
    // Billing
    latestBillAmount: 1800,
    newArrears: 1500,
    amountOverdue: "Rs.1500",
    daysOverdue: "8",
    nextBillDate: new Date('2025-11-25'),
    ageMonths: 18,
    
    // Contact Info
    mobileContactTel: "0701234567",
    contactNumber: "070 123 4567",
    emailAddress: "ravi.kumar@email.com",
    mobileNoConfirmation: true,
    
    // Credit Info
    creditScore: 720,
    creditClassName: "Low Risk",
    billHandlingCodeName: "Standard",
    
    // Management
    accountManager: "Kamal Silva",
    salesPerson: "Sunil Dias",
    
    // Status
    status: "COMPLETED",
    response: "Payment Will Be Done After The Call",
    previousResponse: "Will pay after receiving salary",
    contactHistory: [
      {
        contactDate: getFormattedDate(-1),
        outcome: "Spoke to Customer",
        remark: "Will pay after receiving salary",
        crmAction: "Payment received",
        customerFeedback: "Satisfied",
        creditAction: "Cleared",
        retriedCount: 0,
        promisedDate: getFormattedDate(1),
        paymentMade: true,
        contactedBy: null
      }
    ],
    assignedTo: null,
    assignedDate: getFormattedDate(0)
  },
  {
    // Basic Identity
    accountNumber: "1001234569",
    name: "Ash Kumar",
    region: "Southern",
    rtom: "Galle",
    
    // Product & Service
    productLabel: "Fiber Broadband 50Mbps",
    medium: "FIBER",
    
    // Billing
    latestBillAmount: 4200,
    newArrears: 3500,
    amountOverdue: "Rs.3500",
    daysOverdue: "22",
    nextBillDate: new Date('2025-12-10'),
    ageMonths: 36,
    
    // Contact Info
    mobileContactTel: "0707894561",
    contactNumber: "070 789 4561",
    emailAddress: "ash.kumar@email.com",
    mobileNoConfirmation: false,
    
    // Credit Info
    creditScore: 580,
    creditClassName: "High Risk",
    billHandlingCodeName: "Special Attention",
    
    // Management
    accountManager: "Dinesh Mendis",
    salesPerson: "Chaminda Ranasinghe",
    
    // Status
    status: "OVERDUE",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: [],
    assignedTo: null,
    assignedDate: getFormattedDate(0)
  },
  {
    // Basic Identity
    accountNumber: "1001234570",
    name: "Priya Singh",
    region: "Western",
    rtom: "Negombo",
    
    // Product & Service
    productLabel: "Fiber Broadband 200Mbps",
    medium: "FIBER",
    
    // Billing
    latestBillAmount: 2100,
    newArrears: 1800,
    amountOverdue: "Rs.1800",
    daysOverdue: "12",
    nextBillDate: new Date('2025-11-28'),
    ageMonths: 12,
    
    // Contact Info
    mobileContactTel: "0704567890",
    contactNumber: "070 456 7890",
    emailAddress: "priya.singh@email.com",
    mobileNoConfirmation: true,
    
    // Credit Info
    creditScore: 700,
    creditClassName: "Medium Risk",
    billHandlingCodeName: "Standard",
    
    // Management
    accountManager: "Ruwan Fernando",
    salesPerson: "Ajith Perera",
    
    // Status
    status: "OVERDUE",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: [],
    assignedTo: null,
    assignedDate: getFormattedDate(0)
  },
  {
    // Basic Identity
    accountNumber: "1001234571",
    name: "Sunita Wickramasinghe",
    region: "Northern",
    rtom: "Jaffna",
    
    // Product & Service
    productLabel: "ADSL 4Mbps",
    medium: "ADSL",
    
    // Billing
    latestBillAmount: 3200,
    newArrears: 2500,
    amountOverdue: "Rs.2500",
    daysOverdue: "18",
    nextBillDate: new Date('2025-12-05'),
    ageMonths: 30,
    
    // Contact Info
    mobileContactTel: "0775551234",
    contactNumber: "077 555 1234",
    emailAddress: "sunita.w@email.com",
    mobileNoConfirmation: true,
    
    // Credit Info
    creditScore: 620,
    creditClassName: "Medium Risk",
    billHandlingCodeName: "Standard",
    
    // Management
    accountManager: "Lakshman Silva",
    salesPerson: "Prasad Kumar",
    
    // Status
    status: "PENDING",
    response: "Customer requested extension",
    previousResponse: "No previous contact",
    contactHistory: [
      {
        contactDate: getFormattedDate(-2),
        outcome: "Spoke to Customer",
        remark: "Customer requested extension",
        crmAction: "Extension granted for 5 days",
        customerFeedback: "Will pay within 5 days",
        creditAction: "Monitor closely",
        retriedCount: 0,
        promisedDate: getFormattedDate(5),
        paymentMade: false,
        contactedBy: null
      }
    ],
    assignedTo: null,
    assignedDate: getFormattedDate(0)
  }
];

// Seed function
const seedDatabase = async () => {
  try {
    await connectDB();

    // Clear existing customers
    console.log('Clearing existing customers...');
    await Customer.deleteMany({});
    console.log('Existing customers cleared.');

    // Insert sample customers
    console.log('Inserting sample customers...');
    const customers = await Customer.insertMany(sampleCustomers);
    console.log(`${customers.length} customers inserted successfully!`);

    // Display inserted customers
    console.log('\nInserted Customers:');
    customers.forEach(customer => {
      console.log(`- ${customer.name} (${customer.accountNumber}) - Status: ${customer.status}`);
    });

    console.log('\n✅ Database seeding completed successfully!');
    process.exit(0);
  } catch (error) {
    console.error('❌ Error seeding database:', error);
    process.exit(1);
  }
};

// Run the seed function
seedDatabase();
