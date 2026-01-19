import React, { useState } from "react";
import "./OverduePaymentsTable.css";
import ShowCustomerDetailsModal from "./ShowCustomerDetailsModal";

function OverduePaymentsTable({ payments, onSaveDetails }) {
  const [selectedPayment, setSelectedPayment] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const handleShowDetails = (payment) => {
    setSelectedPayment(payment);
    setIsModalOpen(true);
  };

  const handleCloseModal = () => {
    setIsModalOpen(false);
    setSelectedPayment(null);
  };

  const handleSave = (accountNumber, data) => {
    if (onSaveDetails) {
      onSaveDetails(accountNumber, data);
    }
    handleCloseModal();
  };

  // Get the latest response from contact history
  const getLatestResponse = (payment) => {
    if (payment.contactHistory && payment.contactHistory.length > 0) {
      const latestContact = payment.contactHistory[payment.contactHistory.length - 1];
      return latestContact.remark || payment.response;
    }
    return payment.response || "Not contacted yet";
  };

  return (
    <>
      <div className="overdue-payments-section">
        <div className="section-header">
          <h3>Overdue Payments</h3>
          <a href="#" className="see-all">See All</a>
        </div>
        <div className="table-container">
          <table className="overdue-table">
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
              {payments.map((payment, index) => (
                <tr key={payment.id || payment._id || index}>
                  <td>
                    <div className="customer-info">
                      <strong>{payment.name}</strong>
                      <span className="account-number">Account Number: {payment.accountNumber}</span>
                      <span className="date">{payment.assignedDate || payment.date}</span>
                    </div>
                  </td>
                  <td>
                    <span className="assignment-type">
                      {payment.assignment_type || '-'}
                    </span>
                  </td>
                  <td>
                    <span className="status-badge overdue">
                      {payment.status}
                    </span>
                  </td>
                  <td className="response-text">{getLatestResponse(payment)}</td>
                  <td>
                    <button
                      className="action-button"
                      onClick={() => handleShowDetails(payment)}
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
        customer={selectedPayment}
        onSave={handleSave}
      />
    </>
  );
}

export default OverduePaymentsTable;
