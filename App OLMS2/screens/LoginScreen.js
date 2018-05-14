import React, {Component} from 'react';

import {
    Image,
    Platform,
    ScrollView,
    StyleSheet,
    Text,
    TouchableOpacity,
    View,
    TextInput,
    KeyboardAvoidingView,
    AsyncStorage,
    Dimensions,
    ImageBackground
} from 'react-native';
import {WebBrowser} from 'expo';
import {
    Container,
    Header,
    Content,
    Form,
    Item,
    Input,
    Label,
    Button,
    Card,
    CardItem,
    Body,
    Grid,
    Col,
    Spinner,
    Toast
} from 'native-base';
import { Octicons, Entypo, Ionicons, MaterialCommunityIcons } from '@expo/vector-icons'
import {MonoText} from '../components/StyledText';
import axios from 'axios';
import {HOST} from './host'
export default class LoginScreen extends Component {

    constructor(props) {
        super(props);
        this.state = {
            username: "",
            password: "",
            isFailure: false,
            isLoading: false,
            error: false,
            isUsername: false,
            isPassword: false
            
        }
    }

    handleUsername = (event) => {
        this.setState({username: event});
    }

    handlePassword = (event) => {
        this.setState({password: event});
    }

    handleFetch = async(username, password) => {
        const subject = await axios({
            method: 'post',
            url: HOST+'mobileLogin',
            data: {
                username,
                password
            }
        });
        return subject.data;
    }

    handleLogin = async() => {
        this.setState({isLoading: true});
        if (this.state.username == '') {
            Toast.show({
                text: 'กรุณากรอก PSU Passport',
                buttonText: 'ตกลง',
                type: "warning"
            })
            this.setState({
                isUsername: true,
                isLoading: false
            });
        } else if(this.state.password == ''){
            Toast.show({
                text: 'กรุณากรอก Password',
                buttonText: 'ตกลง',
                type: "warning"
            })
            this.setState({
                isPassword: true,
                isLoading: false
            });
        } else {
            const result = await this.handleFetch(this.state.username, this.state.password);
            if (!result) {
                this.setState({
                    isFailure: true,
                });
                return false;
            } else if (result.subjects == 'Fail') {
                this.setState({
                    error: true,
                    isLoading: false
                })
                Toast.show({
                    text: 'รหัสผ่านไม่ถูกต้อง',
                    buttonText: 'ตกลง',
                    type: "danger"
                })
            } else {
                await AsyncStorage.setItem('username', this.state.username);
                await AsyncStorage.setItem('password', this.state.password);
                await AsyncStorage.setItem('role', result.subjects);
                this.props.handleLogin();
            }
        }
        
    }

    render() {
        const button = this.state.isLoading ? <Spinner color='#9E9E9E' /> : <Text style={styles.text}><MaterialCommunityIcons name='login' style={styles.text}/> เข้าสู่ระบบ</Text>
        //const error = this.state.error ? <Text style={{color: 'red', marginBottom: 10, alignSelf: 'center'}}>รหัสผ่านไม่ถูกต้อง</Text> : <Text></Text>;
        return (
            <ImageBackground source={require('../assets/images/bg.png')} style={styles.backgroundColor}>
            <KeyboardAvoidingView behavior='padding' style={styles.center2}>
                <View style = {styles.view}>
                    <Text style = {styles.title} ><Entypo name='circle' style = {styles.title}/>LMS</Text>  
                </View>
                <View style={styles.center}>
                        <Form style = {styles.forms} >
                            <View>
                            {/* {error} */}
                            <Item  regular>
                                <Text>  </Text>
                                <Octicons style={{color: 'white'}} name='person' size={30} />
                                <Input style={{color: 'white'}} placeholder = "PSU Passport" onChangeText = {this.handleUsername}/ >
                            </Item> 
                            </View>
                            <View>
                            <Item  regular style = {styles.item} >
                                <Text> </Text>
                                <Entypo style={{color: 'white'}} name='lock' size={30} />
                                <Input style={{color: 'white'}} placeholder = "Password" secureTextEntry = {true} onChangeText = { this.handlePassword }/ > 
                            </Item>
                            </View> 
                        </Form> 
                        <Form style = {styles.forms} > 
                            <Button block  style = {styles.button} onPress = { this.handleLogin } > 
                                {button}
                            </Button> 
                        </Form> 
                </View>
            </KeyboardAvoidingView>
            </ImageBackground>
        );
    }
}
const {height, width} = Dimensions.get('window');
const styles = StyleSheet.create({
    backgroundColor: {
        width: width,
        height: height
    },
    view:{
        flex: 0.5,
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    },
    title: {
        fontSize: 80,
        color: 'white',
    },
    center: {
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    },
    center2: {
        flex: 0.8,
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
    },
    item: {
        marginTop: 10,
    },
    forms: {
        width: width/1.25
    },
    button: {
        width: width/1.25,
        marginTop: 20,
        backgroundColor: 'white'
    },
    text: {
        fontSize: 20,
        color: 'slategrey',
        fontWeight: 'bold'
    }

});