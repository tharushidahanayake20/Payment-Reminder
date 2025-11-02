import "./App.css";
import Sidebar from "./components/Sidebar";
import CustomerManagement from "./pages/CustomerManagement";

function App() {
  return (
    <>
      <div className="app-container">
        <Sidebar />
        <div className="main-content">
          <CustomerManagement />
        </div>
      </div>
    </>
  );
}

export default App;
