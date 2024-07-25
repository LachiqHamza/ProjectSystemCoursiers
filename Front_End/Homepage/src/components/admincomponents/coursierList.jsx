import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Table, Button, Modal, Form, Input, InputNumber, notification, DatePicker, Card, Col, Row } from 'antd';
import { EditOutlined, DeleteOutlined, PlusOutlined } from '@ant-design/icons';

const CoursierList = () => {
  const [courciers, setCourciers] = useState([]);
  const [isEditModalVisible, setIsEditModalVisible] = useState(false);
  const [isAddModalVisible, setIsAddModalVisible] = useState(false);
  const [currentCoursier, setCurrentCoursier] = useState(null);
  const [form] = Form.useForm();

  useEffect(() => {
    fetchCourciers();
  }, []);

  const fetchCourciers = async () => {
    try {
      const response = await axios.get('http://localhost:8000/api/courciers/all');
      setCourciers(response.data);
    } catch (error) {
      console.error('Error fetching courciers:', error);
    }
  };

  const handleEdit = (coursier) => {
    setCurrentCoursier(coursier);
    form.setFieldsValue({
      nom: coursier.nom,
      prenom: coursier.prenom,
      tele: coursier.tele,
      email: coursier.email,
      salaire: coursier.salaire,
      password: '', // Clear the password field
    });
    setIsEditModalVisible(true);
  };

  const handleAdd = async () => {
    try {
      const values = form.getFieldsValue();
      await axios.post('http://localhost:8000/api/courciers/add', {
        nom: values.nom,
        prenom: values.prenom,
        tele: values.tele,
        email: values.email,
        password: values.password,
        role: values.role,
        Cin: values.Cin,
        Date_intergration: values.Date_intergration.format('YYYY-MM-DD'),
        salaire: values.salaire,
      });
      notification.success({
        message: 'Success',
        description: 'Coursier added successfully.',
        icon: <PlusOutlined style={{ color: 'green' }} />,
      });
      fetchCourciers(); // Refresh the list
      setIsAddModalVisible(false);
    } catch (error) {
      console.error('Error adding courcier:', error);
    }
  };

  const handleUpdate = async () => {
    try {
      const values = form.getFieldsValue();
      await axios.put(`http://localhost:8000/api/courciers/${currentCoursier.id}/update`, {
        nom: values.nom,
        prenom: values.prenom,
        tele: values.tele,
        salaire: values.salaire,
        passwd: values.password, // Update password if provided
      });
      notification.success({
        message: 'Success',
        description: 'Coursier updated successfully.',
        icon: <EditOutlined style={{ color: 'green' }} />,
      });
      fetchCourciers(); // Refresh the list
      setIsEditModalVisible(false);
    } catch (error) {
      console.error('Error updating courcier:', error);
    }
  };

  const handleDelete = async (id) => {
    try {
      await axios.delete(`http://localhost:8000/api/courciers/${id}`);
      notification.success({
        message: 'Success',
        description: 'Coursier deleted successfully.',
        icon: <DeleteOutlined style={{ color: 'red' }} />,
      });
      fetchCourciers(); // Refresh the list
    } catch (error) {
      console.error('Error deleting courcier:', error);
    }
  };

  const columns = [
    {
      title: 'Nom',
      dataIndex: 'nom',
      key: 'nom',
    },
    {
      title: 'Prénom',
      dataIndex: 'prenom',
      key: 'prenom',
    },
    {
      title: 'Téléphone',
      dataIndex: 'tele',
      key: 'tele',
    },
    {
      title: 'Email',
      dataIndex: 'email',
      key: 'email',
    },
    {
      title: 'Salaire',
      dataIndex: 'salaire',
      key: 'salaire',
    },
    {
      title: 'Actions',
      key: 'actions',
      render: (text, record) => (
        <div style={{ display: 'flex', justifyContent: 'space-between' }}>
          <Button
            icon={<EditOutlined />}
            onClick={() => handleEdit(record)}
            style={{ marginRight: 8 }}
            type="primary"
          />
          <Button
            icon={<DeleteOutlined />}
            onClick={() => handleDelete(record.id)}
            type="danger"
          />
        </div>
      ),
    },
  ];

  return (
    <div style={{ padding: '24px', background: '#fff' }}>
      <Button
        type="primary"
        icon={<PlusOutlined />}
        onClick={() => setIsAddModalVisible(true)}
        style={{ marginBottom: 16 }}
      >
        Add Coursier
      </Button>
      <Table
        columns={columns}
        dataSource={courciers}
        rowKey="id"
        bordered
        pagination={{ pageSize: 10 }}
        scroll={{ x: 'max-content' }}
      />

      <Modal
        title="Add Coursier"
        visible={isAddModalVisible}
        onOk={handleAdd}
        onCancel={() => setIsAddModalVisible(false)}
        okText="Add"
        cancelText="Cancel"
        centered
      >
        <Form form={form} layout="vertical">
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item label="Nom" name="nom" rules={[{ required: true, message: 'Please enter the nom!' }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item label="Prénom" name="prenom" rules={[{ required: true, message: 'Please enter the prénom!' }]}>
                <Input />
              </Form.Item>
            </Col>
          </Row>
          <Form.Item label="Téléphone" name="tele" rules={[{ required: true, message: 'Please enter the telephone!' }]}>
            <Input />
          </Form.Item>
          <Form.Item label="Email" name="email" rules={[{ required: true, message: 'Please enter the email!' }]}>
            <Input />
          </Form.Item>
          <Form.Item label="Password" name="password" rules={[{ required: true, message: 'Please enter the password!' }]}>
            <Input.Password />
          </Form.Item>
          <Form.Item label="Role" name="role" rules={[{ required: true, message: 'Please enter the role!' }]}>
            <Input />
          </Form.Item>
          <Form.Item label="Cin" name="Cin" rules={[{ required: true, message: 'Please enter the Cin!' }]}>
            <Input />
          </Form.Item>
          <Form.Item label="Date Integration" name="Date_intergration" rules={[{ required: true, message: 'Please select the date!' }]}>
            <DatePicker style={{ width: '100%' }} />
          </Form.Item>
          <Form.Item label="Salaire" name="salaire" rules={[{ required: true, message: 'Please enter the salary!' }]}>
            <InputNumber style={{ width: '100%' }} />
          </Form.Item>
        </Form>
      </Modal>

      <Modal
        title="Update Coursier"
        visible={isEditModalVisible}
        onOk={handleUpdate}
        onCancel={() => setIsEditModalVisible(false)}
        okText="Update"
        cancelText="Cancel"
        centered
      >
        <Form form={form} layout="vertical">
          <Row gutter={16}>
            <Col span={12}>
              <Form.Item label="Nom" name="nom" rules={[{ required: true, message: 'Please enter the nom!' }]}>
                <Input />
              </Form.Item>
            </Col>
            <Col span={12}>
              <Form.Item label="Prénom" name="prenom" rules={[{ required: true, message: 'Please enter the prénom!' }]}>
                <Input />
              </Form.Item>
            </Col>
          </Row>
          <Form.Item label="Téléphone" name="tele" rules={[{ required: true, message: 'Please enter the telephone!' }]}>
            <Input />
          </Form.Item>
          <Form.Item label="Email" name="email">
            <Input disabled />
          </Form.Item>
          <Form.Item label="Password" name="password">
            <Input.Password />
          </Form.Item>
          <Form.Item label="Salaire" name="salaire">
            <InputNumber style={{ width: '100%' }} />
          </Form.Item>
        </Form>
      </Modal>
    </div>
  );
};

export default CoursierList;
