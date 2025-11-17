import mongoose from 'mongoose';
import dotenv from 'dotenv';
import { fileURLToPath } from 'url';
import { dirname, join } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

dotenv.config({ path: join(__dirname, '.env') });

import Customer from './models/Customer.js';

const unassignedCustomers = [
  {
    accountNumber: "2001",
    name: "Amal Perera",
    contactNumber: "0771234567",
    amountOverdue: "Rs.5000",
    daysOverdue: "25",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2002",
    name: "Nimal Silva",
    contactNumber: "0772345678",
    amountOverdue: "Rs.7500",
    daysOverdue: "30",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2003",
    name: "Kamal Fernando",
    contactNumber: "0773456789",
    amountOverdue: "Rs.3200",
    daysOverdue: "15",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2004",
    name: "Sunil Jayawardena",
    contactNumber: "0774567890",
    amountOverdue: "Rs.9800",
    daysOverdue: "45",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2005",
    name: "Chaminda Rajapaksa",
    contactNumber: "0775678901",
    amountOverdue: "Rs.4500",
    daysOverdue: "20",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2006",
    name: "Priyanka Wijesinghe",
    contactNumber: "0776789012",
    amountOverdue: "Rs.6200",
    daysOverdue: "35",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2007",
    name: "Dinesh Gunawardena",
    contactNumber: "0777890123",
    amountOverdue: "Rs.8100",
    daysOverdue: "40",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2008",
    name: "Saman Bandara",
    contactNumber: "0778901234",
    amountOverdue: "Rs.2800",
    daysOverdue: "12",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2009",
    name: "Ruwan Kumara",
    contactNumber: "0779012345",
    amountOverdue: "Rs.5600",
    daysOverdue: "28",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2010",
    name: "Lakshitha De Silva",
    contactNumber: "0771112222",
    amountOverdue: "Rs.7200",
    daysOverdue: "33",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2011",
    name: "Mahesh Pathirana",
    contactNumber: "0772223333",
    amountOverdue: "Rs.4100",
    daysOverdue: "18",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2012",
    name: "Sanduni Mendis",
    contactNumber: "0773334444",
    amountOverdue: "Rs.6800",
    daysOverdue: "37",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2013",
    name: "Thilina Rathnayake",
    contactNumber: "0774445555",
    amountOverdue: "Rs.3900",
    daysOverdue: "22",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2014",
    name: "Ishara Gamage",
    contactNumber: "0775556666",
    amountOverdue: "Rs.8500",
    daysOverdue: "42",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  },
  {
    accountNumber: "2015",
    name: "Nadeesha Wickramasinghe",
    contactNumber: "0776667777",
    amountOverdue: "Rs.5400",
    daysOverdue: "26",
    status: "UNASSIGNED",
    response: "Not Contacted Yet",
    previousResponse: "No previous contact",
    contactHistory: []
  }
];

const seedUnassignedCustomers = async () => {
  try {
    await mongoose.connect(process.env.MONGODB_URI);
    console.log('✅ Connected to MongoDB');

    // Remove existing unassigned customers to avoid duplicates
    await Customer.deleteMany({ status: 'UNASSIGNED' });
    console.log('🗑️  Cleared existing unassigned customers');

    // Insert new unassigned customers
    const result = await Customer.insertMany(unassignedCustomers);
    console.log(`✅ Created ${result.length} unassigned customers`);

    console.log('\n📋 Sample customers:');
    result.slice(0, 5).forEach(customer => {
      console.log(`   - ${customer.name} (${customer.accountNumber}) - ${customer.amountOverdue} overdue for ${customer.daysOverdue} days`);
    });

    await mongoose.disconnect();
    console.log('\n✅ Database seeding completed successfully!');
    process.exit(0);
  } catch (error) {
    console.error('❌ Error seeding database:', error);
    process.exit(1);
  }
};

seedUnassignedCustomers();
