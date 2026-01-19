import React, { useState } from "react";
import "./ContactedCustomersTable.css";
import ShowCustomerDetailsModal from "./ShowCustomerDetailsModal";

function ContactedCustomersTable({ customers, onSaveDetails }) {
  const [selectedCustomer, setSelectedCustomer] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleShowDetails = (customer) => {
    setSelectedCustomer(customer);
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedCustomer(null);
  };

  const handleSave = (accountNumber, data) => {
    if (onSaveDetails) {
      onSaveDetails(accountNumber, data);
    }
    handleCloseModal();
  };

  // Get the latest response from contact history
  const getLatestResponse = (customer) => {
    if (customer.contactHistory && customer.contactHistory.length > 0) {
      const latestContact = customer.contactHistory[customer.contactHistory.length - 1];
      return latestContact.remark || customer.response;
    }
    return customer.response || "No response recorded";
  };

  return (
    <>
      <div className="contacted-customers-section">
        <div className="section-header">
          <h3>Contacted Customers</h3>
          <a href="#" className="see-all">See All</a>
        </div>
        <div className="table-container">
          <table className="contacted-table">
            <thead>
              <tr>
                <th>CUSTOMER NAME & OVERDUE PAYMENT DATE</th>
                <th>ASSIGNMENT TYPE</th>
                <th>PAYMENT STATUS</th>
                <th>LATEST RESPONSE</th>
                <th>ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              {customers.map((customer, index) => (
                <tr key={customer.id || customer._id || index}>
                  <td>
                    <div className="customer-info">
                      <strong>{customer.name}</strong>
                      <span className="account-number">Account Number: {customer.accountNumber}</span>
                      <span className="date">{customer.assignedDate || customer.date}</span>
                    </div>
                  </td>
                  <td>
                    <span className="assignment-type">
                      {customer.assignment_type || '-'}
                    </span>
                  </td>
                  <td>
                    <span className={`status-badge ${(customer.status || "").toLowerCase()}`}>
                      {customer.status}
                    </span>
                  </td>
                  <td className="response-text">{getLatestResponse(customer)}</td>
                  <td>
                    <button
                      className="action-button"
                      onClick={() => handleShowDetails(customer)}
                    >
                      SHOW DETAILS
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <ShowCustomerDetailsModal
        isOpen={isModalOpen}
        onClose={handleCloseModal}
        customer={selectedCustomer}
        onSave={handleSave}
      />
    </>
  );
}

export default ContactedCustomersTable;
