import React from 'react';
import { LockOutlined, UserOutlined } from '@ant-design/icons';
import { Button, Checkbox, Form, Input } from 'antd';
import { Link, useNavigate } from 'react-router-dom';
import axios from 'axios';
import styles from './login.css'; // Adjust the path as per your file location

const LoginForm = ({ onRegisterClick }) => {
  const navigate = useNavigate();

  const onFinish = async (values) => {
    try {
      const response = await axios.post('http://127.0.0.1:8000/api/login', {
        email: values.username,
        password: values.password,
      });

      const user = response.data;

      if (user.role === 'client') {
        navigate('/client');
      } else if (user.role === 'admin') {
        navigate('/admin');
      } else if (user.role === 'coursier') {
        navigate('/coursier-dashboard');
      }
    } catch (error) {
      console.error('Error during login:', error);
      alert('Invalid email or password');
    }
  };

  return (
    <div className={styles['login-form-wrapper']}>
      <Form
        name="normal_login"
        className={styles['login-form']}
        initialValues={{
          remember: true,
        }}
        onFinish={onFinish}
      >
        <Form.Item
          name="username"
          rules={[
            {
              required: true,
              message: 'Please input your Username!',
            },
          ]}
        >
          <Input prefix={<UserOutlined className="site-form-item-icon" />} placeholder="Username" />
        </Form.Item>
        <Form.Item
          name="password"
          rules={[
            {
              required: true,
              message: 'Please input your Password!',
            },
          ]}
        >
          <Input
            prefix={<LockOutlined className="site-form-item-icon" />}
            type="password"
            placeholder="Password"
          />
        </Form.Item>
        <Form.Item>
          <Form.Item name="remember" valuePropName="checked" noStyle>
            <Checkbox>Remember me</Checkbox>
          </Form.Item>

          <a className={styles['login-form-forgot']} href="">
            Forgot password
          </a>
        </Form.Item>

        <Form.Item>
          <Button type="primary" htmlType="submit" className={styles['login-form-button']}>
            Log in
          </Button>
          <p>
            Or <Link to="/signup" onClick={onRegisterClick}>register now!</Link>
          </p>
        </Form.Item>
      </Form>
    </div>
  );
};

export default LoginForm;
