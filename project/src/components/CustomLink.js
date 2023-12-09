import { Link, NavLink, useMatch } from 'react-router-dom';

const setActive = ({isActive}) => isActive ? 'active-link' : 'customlink';

const CustomLink = ({children, to, ...props}) => {
    
    const match = useMatch(to);
    
    return (
        
        <NavLink to={to} 
            // className={setActive}
            className={"customlink"}
            {...props}
        >
            {children}
        </NavLink>
        
    )
}

export {CustomLink};