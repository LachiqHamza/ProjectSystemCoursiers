import React, { useState } from 'react';
import { AutoComplete, Button, Cascader, Checkbox, Col, Form, Input, Row, Select, notification } from 'antd';
import { residences } from './residences';
import { Link, useNavigate } from 'react-router-dom';
import './SignUp.css';
import axios from 'axios';

const { Option } = Select;
const formItemLayout = {
  labelCol: {
    xs: { span: 24 },
    sm: { span: 8 },
  },
  wrapperCol: {
    xs: { span: 24 },
    sm: { span: 16 },
  },
};
const tailFormItemLayout = {
  wrapperCol: {
    xs: { span: 24, offset: 0 },
    sm: { span: 16, offset: 8 },
  },
};

const SignUp = () => {
  const [form] = Form.useForm();
  const navigate = useNavigate();

  const onFinish = async (values) => {
    console.log('Received values of form: ', values);
    try {
      const response = await axios.post('http://localhost:8000/api/clients/add', {
        name: values.name,
        lastname: values.lastname,
        email: values.email,
        password: values.password,
        tele: values.phone,
        role: 'client'
      });
      console.log('Server response:', response);

      notification.success({
        message: 'Registration Successful',
        description: 'You have successfully registered. Redirecting to login page...',
      });

      setTimeout(() => {
        navigate('/login');
      }, 2000); // Redirect after 2 seconds
    } catch (error) {
      console.error('There was an error submitting the form!', error);
      notification.error({
        message: 'Registration Failed',
        description: 'There was an issue with your registration. Please try again.',
      });
    }
  };

  const prefixSelector = (
    <Form.Item name="prefix" noStyle>
      <Select style={{ width: 70 }}>
        <Option value="212">+212</Option>
      </Select>
    </Form.Item>
  );

  const [autoCompleteResult, setAutoCompleteResult] = useState([]);
  const onWebsiteChange = (value) => {
    if (!value) {
      setAutoCompleteResult([]);
    } else {
      setAutoCompleteResult(['.com', '.org', '.net'].map((domain) => `${value}${domain}`));
    }
  };
  const websiteOptions = autoCompleteResult.map((website) => ({
    label: website,
    value: website,
  }));

  return (
    <div className="center-container">
      <Form
        {...formItemLayout}
        form={form}
        name="register"
        onFinish={onFinish}
        initialValues={{ residence: ['CasaBlanca', 'Rabat', 'MARRAKECH', '...'], prefix: '212' }}
        style={{ maxWidth: 600 }}
        scrollToFirstError
      >
        <Form.Item
          name="email"
          label="E-mail"
          rules={[
            { type: 'email', message: 'The input is not valid E-mail!' },
            { required: true, message: 'Please input your E-mail!' },
          ]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          name="password"
          label="Password"
          rules={[{ required: true, message: 'Please input your password!' }]}
          hasFeedback
        >
          <Input.Password />
        </Form.Item>

        <Form.Item
          name="confirm"
          label="Confirm Password"
          dependencies={['password']}
          hasFeedback
          rules={[
            { required: true, message: 'Please confirm your password!' },
            ({ getFieldValue }) => ({
              validator(_, value) {
                if (!value || getFieldValue('password') === value) {
                  return Promise.resolve();
                }
                return Promise.reject(new Error('The passwords do not match!'));
              },
            }),
          ]}
        >
          <Input.Password />
        </Form.Item>

        <Form.Item
          name="name"
          label="First Name"
          rules={[{ required: true, message: 'Please input your first name!', whitespace: true }]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          name="lastname"
          label="Last Name"
          rules={[{ required: true, message: 'Please input your last name!', whitespace: true }]}
        >
          <Input />
        </Form.Item>

        <Form.Item
          name="residence"
          label="Habitual Residence"
          rules={[{ type: 'array', required: true, message: 'Please select your habitual residence!' }]}
        >
          <Cascader options={residences} />
        </Form.Item>

        <Form.Item
          name="phone"
          label="Phone Number"
          rules={[{ required: true, message: 'Please input your phone number!' }]}
        >
          <Input addonBefore={prefixSelector} style={{ width: '100%' }} />
        </Form.Item>

        <Form.Item
          name="agreement"
          valuePropName="checked"
          rules={[
            { validator: (_, value) => value ? Promise.resolve() : Promise.reject(new Error('Should accept agreement')) },
          ]}
          {...tailFormItemLayout}
        >
          <Checkbox>
            I have read the <Link to="/Agreement">agreement</Link>
          </Checkbox>
        </Form.Item>
        <Form.Item {...tailFormItemLayout}>
          <Button type="primary" htmlType="submit">Register</Button>
          <Button type="primary" htmlType="button" style={{ marginLeft: '10px' }}>
            <Link to="/login" style={{ color: 'inherit' }}>Go Back to LogIn</Link>
          </Button>
        </Form.Item>
      </Form>
    </div>
  );
};

export default SignUp;
