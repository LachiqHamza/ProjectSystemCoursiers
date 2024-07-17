import React from 'react';
import LoginForm from './login';

const LoginFormWrapper = ({ onRegisterClick }) => {
  return (
    <div style={{ display: 'flex', justifyContent: 'center', alignItems: 'center', minHeight: '100vh' }}>
      <LoginForm onRegisterClick={onRegisterClick} />
    </div>
  );
};

export default LoginFormWrapper;
