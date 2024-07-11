import React from 'react';
import LoginForm from './component/login';
import './component/login.css';
import SignUp from './component/signup';
import Error404 from './component/errorpage';
import Agreement from './component/Agreement';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import WelcomePage from './component/welcomepage';

const App = () => {
  return (
    <Router>
      <div className="app-container">
        
          
          
        </div>
        <Routes>
          <Route path="/" element={<WelcomePage />} />
          <Route path="/login" element={<LoginForm />} />
          <Route path="/signup" element={<SignUp />} />
          <Route path="/agreement" element={<Agreement />} />
          <Route path="*" element={<Error404 />} />

        </Routes>
      
    </Router>
  );
};

export default App;
